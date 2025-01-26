import React from 'react';
import { Link } from 'react-router-dom';

const NavBar = () => {
    return (
        <nav className="navbar">
            <div className="navbar-brand">Prodavnica i Recepti</div>
            <ul className="navbar-menu">
                <li className="navbar-item dropdown">
                    <button className="dropdown-button">Proizvodi</button>
                    <ul className="dropdown-menu">
                        <li className="dropdown-item">Voće</li>
                        <li className="dropdown-item">Povrće</li>
                        <li className="dropdown-item">Meso</li>
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
                <li className="navbar-item">Korpa</li>
            </ul>
        </nav>
    );
};

export default NavBar;
