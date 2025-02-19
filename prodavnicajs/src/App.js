import './App.css';
import RegisterPage from './components/RegisterPage';
import LogInPage from './components/LogInPage';
import NavBar from './components/NavBar';
import Homepage from './components/Homepage';
import Products from './components/Products';
import SearchProducts from './components/SearchProducts';
import Cart from './components/Cart';
import { CartProvider } from './context/CartContext';
import Purchase from './components/Purchase';
import Receipts from "./components/Receipts";
import OneReceipt from "./components/OneReceipt";

import { BrowserRouter, Routes, Route} from 'react-router-dom';
import SearchReceipts from './components/SearchReceipts';

function App() {

  return (
    <CartProvider>
      <BrowserRouter>
      <NavBar /> {/* Navigacioni bar */}
      <Routes>
        <Route path="/" element={<Homepage />} />
        <Route path="/login" element={<LogInPage />} />
        <Route path="/register" element={<RegisterPage />} />
        <Route path="/proizvodi" element={<Products />} />
        <Route path="/proizvodi/pretraga" element={<SearchProducts />} />
        <Route path="/korpa" element={<Cart />} />
        <Route path="/kupovina" element={<Purchase />} />
        <Route path="/recepti" element={<Receipts />} />
        <Route path="/recepti/:id" element={<OneReceipt />} />
        <Route path="/recepti/pretraga" element={<SearchReceipts />} />
        


       </Routes>

      </BrowserRouter>
    </CartProvider>
  );
}

export default App;

