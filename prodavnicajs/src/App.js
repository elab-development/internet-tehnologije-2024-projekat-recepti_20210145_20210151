import './App.css';
import RegisterPage from './components/RegisterPage';
import LogInPage from './components/LogInPage';
import NavBar from './components/NavBar';
import Homepage from './components/Homepage';

import { BrowserRouter, Routes, Route} from 'react-router-dom';

function App() {
  return (
    <BrowserRouter className="App">
      <NavBar /> {/* Navigacioni bar */}
      <Routes>
        <Route path="/" element={<Homepage />} />
        <Route path="/login" element={<LogInPage />} />
        <Route path="/register" element={<RegisterPage />} />
       </Routes>

    </BrowserRouter>
  );
}

export default App;

