/*import React from 'react';*/
import React, { useState, useContext, useEffect, } from 'react';
import { Link } from 'react-router-dom';
import { BsCart3 } from "react-icons/bs";
import { FaBars, FaTimes } from 'react-icons/fa';
import { CartContext } from '../context/CartContext';


const NavBar = () => {
    
    const { cart } = useContext(CartContext);
    const [cartCount, setCartCount] = useState(0);
    useEffect(() => {
        setCartCount(cart.length);
    }, [cart]);

    const [isMenuOpen, setIsMenuOpen] = useState(false);

    const toggleMenu = () => {
        setIsMenuOpen(!isMenuOpen);
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
                    <button className="dropdown-button">Recepti</button>
                    <ul className="dropdown-menu">
                        <li className="dropdown-item">Predjelo</li>
                        <li className="dropdown-item">Glavno jelo</li>
                        <li className="dropdown-item">Desert</li>
                    </ul>
                </li>
                <li className="navbar-item">
                    <Link to="/login">Prijava</Link>
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
