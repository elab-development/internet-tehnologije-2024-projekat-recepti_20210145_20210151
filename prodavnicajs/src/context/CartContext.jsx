import React, { createContext, useState } from 'react';

export const CartContext = createContext();

export const CartProvider = ({ children }) => {
    const [cart, setCart] = useState([]);

    const fetchCart = async () => {
        try {
            const response = await fetch('http://localhost:8000/api/korpa', {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            });
            const data = await response.json();
            console.log("Podaci iz API-ja:", data);
    
            if (data.proizvodi) {
                if (Array.isArray(data.proizvodi)) {
                    setCart(data.proizvodi); // Postavljamo proizvode iz korpe
                } else {
                    console.error("API ne vraća niz! Dobijeno:", data.proizvodi);
                    setCart([]);
                }
            } else {
                console.error("API ne vraća proizvode:", data);
                setCart([]);
            }
        } catch (error) {
            console.error('Greška pri učitavanju korpe:', error);
        }
    };
    

    const addToCart = async (product) => {
        try {
            const response = await fetch('http://localhost:8000/api/korpa/add-product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({ proizvod_id: product.id, kolicina_proizvoda: 1 })
            });

            if (response.ok) {
                await fetchCart();
            }
        } catch (error) {
            console.error('Greška pri dodavanju proizvoda:', error);
        }
    };

    const updateQuantity = async (productId, action) => {
        try {
            const response = await fetch('http://localhost:8000/api/korpa/update-product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({ proizvod_id: productId, action }) // action: "increase" ili "decrease"
            });

            if (response.ok) {
                fetchCart();
            }
        } catch (error) {
            console.error('Greška pri ažuriranju količine:', error);
        }
    };

    return (
        //sta je sve dostupno drugim komponentama
        <CartContext.Provider value={{ cart, setCart, addToCart, updateQuantity, fetchCart  }}> 
            {children}
        </CartContext.Provider>
    );
};
