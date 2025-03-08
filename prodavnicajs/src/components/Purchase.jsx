import React, { useState, useContext} from 'react';
import { useLocation, useNavigate } from 'react-router-dom'; // Za preuzimanje podataka iz prethodne stranice
import { CartContext } from '../context/CartContext';
import axios from 'axios';

const Purchase = () => {

    const { clearCart } = useContext(CartContext);

    const location = useLocation();
    const navigate = useNavigate();  // Za preusmeravanje na drugoj URL
    const total = location.state?.total; 
    console.log("Cena: ", total); // Preuzimanje ukupne cene iz prethodne stranice

    const [paymentMethod, setPaymentMethod] = useState('cash'); // Pocetno je pouzecem
    const [cardNumber, setCardNumber] = useState('');
    const [deliveryAddress, setDeliveryAddress] = useState('');
    const [error, setError] = useState('');

    const handlePaymentChange = (e) => {
        setPaymentMethod(e.target.value);
    };
    const handleSubmit = async (e) => {
        e.preventDefault();

        if (!deliveryAddress) {
            setError('Adresa dostave je obavezna!');
            return;
        }
        const data = {
            ukupna_cena: total,
            nacin_placanja: paymentMethod,
            adresa_dostave: deliveryAddress,
            ...(paymentMethod === 'card' && { broj_kartice: cardNumber }) // Ako je placanje karticom, dodaj broj kartice
        };
        console.log("Ukupna cena: ", data.ukupna_cena);
        try {
            const response = await axios.post('http://127.0.0.1:8000/api/kupovina/store', data, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
            });
            console.log('Uspesna kupovina', response.data);
            alert("Uspesna kupovina!");

            console.log('Data:', data);
            clearCart();
            navigate("/");
            // Usmeravanje na stranicu sa potvrdom
        } catch (err) {
            console.error('Došlo je do greške', err);
            setError('Došlo je do greške, pokušajte ponovo.');
        }
    };

    return (
        <div className="purchase">
            <h2>Potvrda kupovine</h2>
            <p>Ukupna cena: {total} RSD</p>

            <form onSubmit={handleSubmit}>
                <div>
                    <label>
                        Način plaćanja:
                        <select value={paymentMethod} onChange={handlePaymentChange}>
                            <option value="cash">Pouzećem</option>
                            <option value="card">Kartica</option>
                        </select>
                    </label>
                </div>

                {paymentMethod === 'card' && (
                    <div>
                        <label>
                            Broj kartice:
                            <input
                                type="text"
                                value={cardNumber}
                                onChange={(e) => setCardNumber(e.target.value)}
                                required={paymentMethod === 'card'}
                            />
                        </label>
                    </div>
                )}

                <div>
                    <label>
                        Adresa dostave:
                        <input
                            type="text"
                            value={deliveryAddress}
                            onChange={(e) => setDeliveryAddress(e.target.value)}
                            required
                        />
                    </label>
                </div>

                {error && <p className="error">{error}</p>}

                <button type="submit">Potvrdi kupovinu</button>
            </form>
        </div>
    );
};

export default Purchase;
