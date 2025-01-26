import logo from './logo.svg';
import './App.css';
import LogInPage from './components/LogInPage';
import NavBar from './components/NavBar';
import { BrowserRouter, Routes, Route} from 'react-router-dom';

function App() {
  return (
    <BrowserRouter className="App">
      <NavBar /> {/* Navigacioni bar */}
      <Routes>
        <Route path="/login" element={<LogInPage />} />
       </Routes>

    </BrowserRouter>
  );
}

export default App;

