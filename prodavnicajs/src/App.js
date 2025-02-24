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
import Recipes from "./components/Recipes";
import OneRecipe from "./components/OneRecipe";

import { BrowserRouter, Routes, Route} from 'react-router-dom';
import SearchRecipes from './components/SearchRecipes';

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
        <Route path="/recepti" element={<Recipes />} />
        <Route path="/recepti/:id" element={<OneRecipe />} />
        <Route path="/recepti/pretraga" element={<SearchRecipes />} />
        


       </Routes>

      </BrowserRouter>
    </CartProvider>
  );
}

export default App;

