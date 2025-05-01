
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { CartProvider, useCart } from '../context/CartContext';
import OneRecipe from '../components/OneRecipe';
import { BrowserRouter as Router } from 'react-router-dom';
import { vi } from 'vitest';
import React from 'react';

// Mock API poziva i alert funkcije
beforeEach(() => {
  global.fetch = vi.fn().mockResolvedValue({
    json: vi.fn().mockResolvedValue({
      id: 81,
      naziv: 'karbonara',
      tip_jela: 'Glavno jelo',
      vreme_pripreme: 20,
      slika: '/storage/recepti_image/karbonara.jpeg',
      opis_pripreme: 'Priprema paste sa jajima i sirom.',
      proizvodi: [
        {
          id: 201,
          naziv: 'spageti',
          pivot: {
            kolicina: 200,
            merna_jedinica: 'g',
          },
        },
      ],
    }),
  });
  global.alert = vi.fn(); // Mock alert
});

afterEach(() => {
  localStorage.clear();
  vi.restoreAllMocks(); // Briše sve mock-ove nakon svakog testa
});

describe('OneRecipe Component', () => {
  it('should render loading state initially and wait for data to load', async () => {
    renderComponent();
    expect(screen.getByText('Učitavanje...')).toBeInTheDocument();
    await waitFor(() => expect(screen.getByText('karbonara')).toBeInTheDocument());
  });

  it('should fetch and display recipe data', async () => {
    renderComponent();
    await waitFor(() => {
      expect(screen.getByText('karbonara')).toBeInTheDocument();
      expect(screen.getByText('Glavno jelo')).toBeInTheDocument();
      expect(screen.getByText('20 min')).toBeInTheDocument();
      expect(screen.getByText('spageti')).toBeInTheDocument();
    });
  });

  it('should handle API errors gracefully', async () => {
    fetch.mockRejectedValueOnce(new Error('API greška'));
    renderComponent();
    await waitFor(() => {
      expect(screen.getByText('Učitavanje...')).toBeInTheDocument();
    });
    await waitFor(() => {
      expect(screen.queryByText('karbonara')).toBeNull();
    });
  });

  it('should show modal when user clicks on a product', async () => {
    renderComponent();
    await waitFor(() => {
      expect(screen.getByText('spageti')).toBeInTheDocument();
    });

    fireEvent.click(screen.getByRole('button', { name: /Dodaj u korpu/i }));

    await waitFor(() => {
      expect(screen.getByText('Da li ste sigurni da želite da dodate ovaj proizvod u korpu?')).toBeInTheDocument();
    });
  });

  it('should add product to cart when user is logged in', async () => {
    const addToCartMock = vi.fn();

    function MockCartProvider({ children }) {
      return (
        <CartProvider>
          <CartConsumerWrapper addToCart={addToCartMock}>{children}</CartConsumerWrapper>
        </CartProvider>
      );
    }
    function CartConsumerWrapper({ addToCart, children }) {
      const cart = useCart();
      cart.addToCart = addToCart;
      return children;
    }
    renderComponent(MockCartProvider);
    await waitFor(() => {
      expect(screen.getByText('spageti')).toBeInTheDocument();
    });

    fireEvent.click(screen.getByRole('button', { name: /Dodaj u korpu/i }));

    await waitFor(() => {
      expect(screen.getByText('Da li ste sigurni da želite da dodate ovaj proizvod u korpu?')).toBeInTheDocument();
    });

    fireEvent.click(screen.getByText('Da'));

    await waitFor(() => {
      expect(addToCartMock).toHaveBeenCalledWith({
        id: 201,
        naziv: 'spageti',
        pivot: {
          kolicina: 200,
          merna_jedinica: 'g',
        },
      });
    });
  });
});

function renderComponent(Provider = CartProvider) {
  render(
    <Router>
      <Provider>
        <OneRecipe id={81} />
      </Provider>
    </Router>
  );
}
