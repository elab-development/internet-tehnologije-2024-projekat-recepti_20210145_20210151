import './App.css';
import RegisterPage from './components/RegisterPage';
import LogInPage from './components/LogInPage';
import NavBar from './components/NavBar';
import Homepage from './components/Homepage';
import Products from './components/Products';
import SearchProducts from './components/SearchProducts';

import { BrowserRouter, Routes, Route} from 'react-router-dom';

function App() {
  return (
    <BrowserRouter className="App">
      <NavBar /> {/* Navigacioni bar */}
      <Routes>
        <Route path="/" element={<Homepage />} />
        <Route path="/login" element={<LogInPage />} />
        <Route path="/register" element={<RegisterPage />} />
        <Route path="/proizvodi" element={<Products />} />
        <Route path="/proizvodi/pretraga" element={<SearchProducts />} />
        
       </Routes>

    </BrowserRouter>
  );
}

export default App;

