import React, { createContext, useState } from 'react';

export const CartContext = createContext();

export const CartProvider = ({ children }) => {

    const [cart, setCart] = useState([]);


    const fetchCart = async () => {
        try {
            const response = await fetch('http://localhost:8000/api/korpa', {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            });
            const data = await response.json();
            console.log("Podaci iz API-ja:", data);
    
            if (data.proizvodi) {
                if (Array.isArray(data.proizvodi)) {
                    setCart(data.proizvodi); // Postavljamo proizvode iz korpe
                } else {
                    console.error("API ne vraća niz! Dobijeno:", data.proizvodi);
                    setCart([]);
                }
            } else {
                console.error("API ne vraća proizvode:", data);
                setCart([]);
            }
        } catch (error) {
            console.error('Greška pri učitavanju korpe:', error);
        }
    };
    
    const addToCart = async (product) => {
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                console.error("Nema tokena, korisnik nije ulogovan!");
                return;
            }
    
            // Provera da li proizvod već postoji u korpi
            const existingProduct = cart.find(item => item.proizvod_id === product.id);
            let quantity = 1;
    
            if (existingProduct) {
                // Ako proizvod već postoji, uzmi trenutnu količinu i povećaš je za 1
                quantity = existingProduct.kolicina + 1; 
                await updateQuantity(product.id, "increase");  // Ažuriranje količine
                alert("Količina proizvoda je povećana.");
            } else {
                // Ako proizvod ne postoji, dodaješ ga u korpu
                const response = await fetch('http://localhost:8000/api/korpa/add-product', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    body: JSON.stringify({
                        proizvod_id: product.id,
                        kolicina_proizvoda: quantity,
                    }),
                });
    
                const data = await response.json();
    
                if (response.ok) {
                    // Osveži korpu nakon što je proizvod dodat
                    await fetchCart();  // Očekuj da se API poziv završi pre nego što osvežiš UI
                    alert("Uspesno dodavanje proizvoda u korpu: " + data.message);
                } else {
                    alert("Greška: " + data.message);
                }
            }
        } catch (error) {
            console.error('Greška pri dodavanju proizvoda:', error);
            alert('Došlo je do greške pri dodavanju proizvoda u korpu.');
        }
    };
    
    const updateQuantity = async (proizvod_id, action) => {
        try {
            const updatedCart = [...cart];  // Kopiraj trenutni state
            const product = updatedCart.find(item => item.pivot.proizvod_id === proizvod_id);
            if (!product) {
                console.error("Proizvod nije pronađen u korpi.");
                return;
            }
   
            let newQuantity = action === "increase" ? product.pivot.kolicina_proizvoda + 1 : product.pivot.kolicina_proizvoda - 1;
            if (newQuantity < 1) {
                console.warn("Količina ne može biti manja od 1.");
                return;
            }
   
            product.pivot.kolicina_proizvoda = newQuantity;  // Ažuriraj količinu odmah u lokalnom stavu
            setCart(updatedCart);  // Ažuriraj stanje korpe
   
            const response = await fetch('http://localhost:8000/api/korpa/update-product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({
                    proizvod_id: proizvod_id,
                    kolicina: newQuantity,
                    korpa_id: product.pivot.korpa_id
                })
            });
   
            if (response.ok) {
                await fetchCart();  // Sinhronizuj sa serverom nakon lokalnih promena
            } else {
                console.error("Greška pri ažuriranju količine:", response);
            }
        } catch (error) {
            console.error("Greška pri ažuriranju količine proizvoda:", error);
        }
    };
   
    return (
        //sta je sve dostupno drugim komponentama
        <CartContext.Provider value={{ cart, setCart, addToCart, fetchCart, updateQuantity  }}> 
            {children}
        </CartContext.Provider>
    );
};

