import './App.css';
import RegisterPage from './components/RegisterPage.jsx';
import LogInPage from './components/LogInPage.jsx';
import NavBar from './components/NavBar.jsx';
import Homepage from './components/Homepage.jsx';
import Products from './components/Products.jsx';
import SearchProducts from './components/SearchProducts.jsx';
import Cart from './components/Cart.jsx';
import { CartProvider } from './context/CartContext.jsx';
import Purchase from './components/Purchase.jsx';
import Recipes from "./components/Recipes.jsx";
import OneRecipe from "./components/OneRecipe.jsx";
import RecipeFinder from './components/RecipeFinder.jsx';
import { BrowserRouter, Routes, Route} from 'react-router-dom';
import SearchRecipes from './components/SearchRecipes.jsx';


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
        <Route path="/recipe-finder" element={<RecipeFinder />} />
       </Routes>
      </BrowserRouter>
    </CartProvider>
  );
}

export default App;

