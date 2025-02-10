
import React from 'react'
import axios from 'axios';
import{useState} from "react";
import { useNavigate } from 'react-router-dom';  // Import useNavigate

const LoginPage = () => {
    const [userData, setUserData] = useState({email:"", password:""});
    const navigate = useNavigate();
    
    function handleInput (e){
        //console.log(e);
        let newUserData = userData;
        newUserData[e.target.name] = e.target.value;
        //console.log(newUserData);
        setUserData(newUserData);
    }

    function handleLogIn (e) {
        e.preventDefault();
        //axios.defaults.withCredentials = true;
        axios.post("http://127.0.0.1:8000/api/login", userData)
        .then((res) => {
            console.log(res.data);
            localStorage.setItem('token', res.data.access_token); // Sačuvaj token
            navigate("/");
        })
        .catch((e) => {
            console.log(e);
        });
    }
  return (
    <div className="login-container">
    <h1 className="login-title">Dobrodošli u našu prodavnicu</h1>
    <p className="login-subtitle">Prijavite se kako biste istražili recepte i namirnice</p>
    <form onSubmit={handleLogIn}>
        <div className="login-form">
        <input 
            type="email" 
            placeholder="Email" 
            className="login-input"
            name="email" 
            onInput = {handleInput}
        />
        <input 
            type="password" 
            placeholder="Lozinka" 
            className="login-input"
            name = "password"
            onInput = {handleInput}
        />
        <button type="submit" className="login-button">Prijavi se</button>
        <div className="register-link">
        <a href="/register">Kreirajte nalog</a>
        </div>
        </div>
    </form>
  </div>
  )
}

export default LoginPage