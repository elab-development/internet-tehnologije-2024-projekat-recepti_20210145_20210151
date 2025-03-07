
import React from 'react'
import axios from 'axios';
import{useState} from "react";
import { useNavigate } from 'react-router-dom';  // Import useNavigate

const LoginPage = () => {
    const [userData, setUserData] = useState({email:"", password:""});
    const navigate = useNavigate();

    const handleChange = (e) => {
        setUserData({ ...userData, [e.target.name]: e.target.value });
      };
        function handleLogIn (e) {
            e.preventDefault();
            console.log(userData);
            axios.post("http://127.0.0.1:8000/api/login", userData)
            .then((res) => {
                console.log(res.data);
                localStorage.setItem('token', res.data.access_token); // Sacuvaj token
                const token = localStorage.getItem('token'); // Proveri da li je token sacuvan
                console.log("Token nakon prijave:", token); // Ispisuj token u konzolu
                navigate("/");
            })
            .catch((e) => {
                console.log(e);
            });
        }
        
  return (
    <div className="login-container">
    <h1 className="login-title">Dobrodošli u našu prodavnicu</h1>
    <p className="login-subtitle">Prijavite se kako biste istražili recepte i namirnice!</p>
    <form onSubmit={handleLogIn}>
        <div className="login-form">
        <input 
            type="email" 
            placeholder="Email" 
            className="login-input"
            name="email" 
            value={userData.email}
            onChange = {handleChange}
        />
        <input 
            type="password" 
            placeholder="Lozinka" 
            className="login-input"
            name = "password"
            value={userData.password}
            onChange = {handleChange}
        />
        <button type="submit" className="login-button">Prijavi se</button>
        <div className="register-link"> 
            <a href="/register">Nemate nalog? Kreirajte ga.</a>
        </div>
        </div>
    </form>
  </div>
  )
}

export default LoginPage