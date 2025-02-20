import React, { useState, useContext, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';  // useNavigate za preusmeravanje
import { BsCart3 } from "react-icons/bs";
import { FaBars, FaTimes } from 'react-icons/fa';
import { CartContext } from '../context/CartContext';

const NavBar = () => {
    const { cart } = useContext(CartContext);
    const [cartCount, setCartCount] = useState(0);
    const navigate = useNavigate();  // Koristimo useNavigate za preusmeravanje

    useEffect(() => {
        setCartCount(cart.length);
    }, [cart]);

    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const toggleMenu = () => {
        setIsMenuOpen(!isMenuOpen);
    };

    // Funkcija za odjavu
    const handleLogout = () => {
        localStorage.removeItem('token'); // Ukloni token iz localStorage
        navigate('/login');  // Preusmeri korisnika na stranicu za prijavu
    };

    return (
        <nav className="navbar">
            <div className="navbar-brand">
                <Link to="/">InstaRecipe</Link>
            </div>
            <div className="navbar-toggle" onClick={toggleMenu}>
                {isMenuOpen ? <FaTimes /> : <FaBars />}
            </div>
            <ul className={`navbar-menu ${isMenuOpen ? 'active' : ''}`}>
                <li className="navbar-item dropdown">
                    <Link to="/proizvodi" className="dropdown-button">Proizvodi</Link>
                    <ul className="dropdown-menu">
                        <li className="dropdown-item"><Link to="/proizvodi/pretraga?kategorija=Voce">Voće</Link></li>
                        <li className="dropdown-item"><Link to="/proizvodi/pretraga?kategorija=Povrce">Povrće</Link></li>
                        <li className="dropdown-item"><Link to="/proizvodi/pretraga?kategorija=Meso">Meso</Link></li>
                    </ul>
                </li>
                <li className="navbar-item dropdown">
                    <Link to="/recepti" className="dropdown-button">Recepti</Link>
                    <ul className="dropdown-menu">
                        <li className="dropdown-item"><Link to="/recepti/pretraga?tip_jela=predjelo">Predjelo</Link></li>
                        <li className="dropdown-item"><Link to="/recepti/pretraga?tip_jela=glavno jelo">Glavno jelo</Link></li>
                        <li className="dropdown-item"><Link to="/recepti/pretraga?tip_jela=desert">Desert</Link></li>
                        <li className="dropdown-item"><Link to="/recepti/pretraga?tip_jela=salata">Salata</Link></li>
                    </ul>
                </li>
                <li className="navbar-item">
                    {/* Proveravamo da li je korisnik ulogovan da bismo prikazali dugme za odjavu */}
                    {localStorage.getItem('token') ? (
                        <button onClick={handleLogout} style ={{backgroundColor: 'transparent',   color: 'white',border:'none'}} >Odjavi se</button>
                    ) : (
                        <Link to="/login">Prijava</Link>
                    )}
                </li>
                <li className="navbar-item">
                    <Link to="/korpa" className="cart-icon-btn">
                        <BsCart3 />
                        {cartCount > 0 && <span className="cart-count">{cartCount}</span>}
                    </Link>
                </li>
            </ul>
        </nav>
    );
};

export default NavBar;
