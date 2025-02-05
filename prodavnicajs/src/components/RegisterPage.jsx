import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';  // Import useNavigate

const RegisterPage = () => {
    const [userData, setUserData] = useState({name:"", email:"", password:"", password_confirmation:""});
    const navigate = useNavigate();
    function handleInput (e){
        //console.log(e);
        console.log(userData);
        let newUserData = userData;
        newUserData[e.target.name] = e.target.value;
        //console.log(newUserData);
        setUserData(newUserData);
    }

    function handleRegister (e) {
        e.preventDefault();
        if (userData.password !== userData.password_confirmation) {
            alert("Lozinke se ne poklapaju!");
            return;
          }
        //axios.defaults.withCredentials = true;
        axios
        .post("http://127.0.0.1:8000/api/register", userData)
        .then((res) => {
            console.log(res.data);
            navigate("/");
        })
        .catch((e) => {
            console.log(e);
        });
    }
  return (
    <div className="registration-container">
    <h1 className="registration-title">Postanite novi korisnik!</h1>
    <p className="registration-subtitle">Kreirajte nalog kako biste mogli da ostvarite kupovinu!</p>
    <form onSubmit={handleRegister}>
        <div className="registration-form">
        <input 
            type="text" 
            placeholder="Ime i prezime" 
            className="registration-input"
            name="name" 
            //value={userData.name}
            onInput = {handleInput}
        />
        <input 
            type="email" 
            placeholder="Email" 
            className="registration-input"
            name="email" 
            //value={userData.email}
            onInput = {handleInput}
        />
        <input 
            type="password" 
            placeholder="Lozinka" 
            className="registration-input"
            name = "password"
            //value={userData.password}
            onInput = {handleInput}
        />
        <input 
            type="password" 
            placeholder="Potvrdi lozinku" 
            className="registration-input"
            name = "password_confirmation"
            //value={userData.password_confirmation}
            onInput = {handleInput}
        />
        <button type="submit" className="registration-button">Registruj se</button>
        </div>
    </form>
  </div>
  )
}

export default RegisterPage
