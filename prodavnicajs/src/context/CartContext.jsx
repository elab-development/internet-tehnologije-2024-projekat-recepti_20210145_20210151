import React, { createContext, useState, useEffect, useContext } from 'react';

const CartContext = createContext();

export { CartContext };


export const CartProvider = ({ children }) => {

    const [cart, setCart] = useState([]);

    useEffect(() => {
        console.log("Korpa ažurirana:", cart);
    }, [cart]);
    const fetchCart = async () => {
        try {
            const response = await fetch('http://localhost:8000/api/korpa', {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            });
            const data = await response.json();
     
            if (data.proizvodi) {
                if (Array.isArray(data.proizvodi)) {
                    setCart(data.proizvodi); // Postavljanje proizvoda u korpu
                } else {
                    setCart([]);
                }
            } else {
                setCart([]);
            }
        } catch (error) {
            console.error('Greška pri učitavanju korpe:', error);
        }
     };
     
     /*const addToCart = async (product) => {
        try {
            console.log("Proizvod koji se dodaje u korpu", product);
    
            const token = localStorage.getItem('token');
            if (!token) {
                alert("Morate biti prijavljeni da biste mogli uspešno obaviti kupovinu!");
                return;
            }
    
            const existingProduct = cart.find(item => item.pivot.proizvod_id === product.id);
    
            let quantity = 1;
            if (existingProduct) {
                quantity = existingProduct.pivot.kolicina_proizvoda + 1;
                await updateQuantity(product.id, "increase");
                alert("Količina proizvoda je povećana.");
            } else {
                // Dodavanje proizvoda direktno u state
                setCart(prevCart => [
                    ...prevCart,
                    { ...product, pivot: { proizvod_id: product.id, kolicina_proizvoda: quantity } }
                ]);
                alert("Proizvod uspešno dodat u korpu!");
            }
    
            // API poziv za dodavanje proizvoda u bazu
            const response = await fetch('http://localhost:8000/api/korpa/add-product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    proizvod_id: product.id,
                    kolicina: quantity
                })
            });
    
            if (response.ok) {
                const data = await response.json();
                console.log("Uspešno dodato u bazu:", data);
            } else {
                console.error("Greška pri dodavanju proizvoda u bazu:", response);
                alert("Greška pri dodavanju proizvoda u korpu.");
            }
    
        } catch (error) {
            console.error("Greška pri dodavanju proizvoda:", error);
        }
    };*/
    const addToCart = async (product) => {
        try {
            console.log("Proizvod koji se dodaje u korpu", product);
    
            const token = localStorage.getItem('token');
            if (!token) {
                alert("Morate biti prijavljeni da biste mogli uspešno obaviti kupovinu!");
                return;
            }
    
            const existingProduct = cart.find(item => item.pivot.proizvod_id === product.id);
    
            let quantity = 1;
            let korpa_id = null; // Inicijalizuj korpa_id kao null
    
            if (existingProduct) {
                quantity = existingProduct.pivot.kolicina_proizvoda + 1;
                await updateQuantity(product.id, "increase");
                alert("Količina proizvoda je povećana.");
            } else {
                // Dodavanje proizvoda direktno u state
                setCart(prevCart => [
                    ...prevCart,
                    { 
                        ...product, 
                        pivot: { proizvod_id: product.id, kolicina_proizvoda: quantity } 
                    }
                ]);
                alert("Proizvod uspešno dodat u korpu!");
            }
    
            // API poziv za dodavanje proizvoda u bazu
            const response = await fetch('http://localhost:8000/api/korpa/add-product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    proizvod_id: product.id,
                    kolicina: quantity
                })
            });
    
            if (response.ok) {
                const data = await response.json();
                console.log("Uspešno dodato u bazu:", data);
                korpa_id = data.korpa_id; // Preuzmi korpa_id iz odgovora
                // Dodaj korpa_id u stanje korpe
                setCart(prevCart => prevCart.map(item => 
                    item.pivot.proizvod_id === product.id 
                    ? { ...item, pivot: { ...item.pivot, korpa_id: korpa_id } } 
                    : item
                ));
            } else {
                console.error("Greška pri dodavanju proizvoda u bazu:", response);
                alert("Greška pri dodavanju proizvoda u korpu.");
            }
    
        } catch (error) {
            console.error("Greška pri dodavanju proizvoda:", error);
        }
    };
    
    
     

    /*const updateQuantity = async (proizvod_id, action) => {
        try {
            const updatedCart = [...cart];  // Kopiraj trenutni state
            const product = updatedCart.find(item => item.pivot.proizvod_id === proizvod_id);
            if (!product) {
                console.error("Proizvod nije pronađen u korpi.");
                return;
            }

            let newQuantity = action === "increase"
                ? product.pivot.kolicina_proizvoda + 1
                : product.pivot.kolicina_proizvoda - 1;

            if (newQuantity < 1) {
                await removeProduct(proizvod_id);
                return;
            }

            // Ažuriraj stanje korpe koristeći prevCart za sigurnost
            setCart(prevCart => {
                const updatedCart = prevCart.map(item =>
                    item.pivot.proizvod_id === proizvod_id
                        ? { ...item, pivot: { ...item.pivot, kolicina_proizvoda: newQuantity } }
                        : item
                );
                console.log("Novo stanje korpe:", updatedCart); // Provera ažuriranja state-a
                return updatedCart;
            });
            

            const response = await fetch('http://localhost:8000/api/korpa/update-product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({
                    proizvod_id: proizvod_id,
                    kolicina_proizvoda: newQuantity,
                    korpa_id: product.pivot.korpa_id
                })
            });
            if (!response.ok) {
                console.error("Greška pri ažuriranju količine:", response.status, await response.text());
                return;
            }

            if (response.ok) {
                const data = await response.json();
                console.log("Uspešno ažurirano:", data);
                await fetchCart();  // Sinhronizuj sa serverom nakon lokalnih promena
            } else {
                console.error("Greška pri ažuriranju količine:", response);
                return;
            }
        } catch (error) {
            console.error("Greška pri ažuriranju količine proizvoda:", error);
        }
    };*/

    const updateQuantity = async (proizvod_id, action) => {
        try {
            const updatedCart = [...cart];  // Kopiraj trenutni state
            const product = updatedCart.find(item => item.pivot.proizvod_id === proizvod_id);
            if (!product) {
                console.error("Proizvod nije pronađen u korpi.");
                return;
            }
    
            let newQuantity = action === "increase"
                ? product.pivot.kolicina_proizvoda + 1
                : product.pivot.kolicina_proizvoda - 1;
    
            if (newQuantity < 1) {
                await removeProduct(proizvod_id);  // Funkcija koja uklanja proizvod
                return;
            }
    
            // Ažuriraj stanje korpe
            setCart(prevCart => {
                const updatedCart = prevCart.map(item =>
                    item.pivot.proizvod_id === proizvod_id
                        ? { ...item, pivot: { ...item.pivot, kolicina_proizvoda: newQuantity } }
                        : item
                );
                console.log("Novo stanje korpe:", updatedCart); // Provera ažuriranja state-a
                return updatedCart;
            });
    
           
            // Posaljite ažuriranje na server
            const response = await fetch('http://localhost:8000/api/korpa/update-product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({
                    proizvod_id: proizvod_id,
                    kolicina_proizvoda: newQuantity,
                    korpa_id: product.pivot.korpa_id
                })
            });
            console.log(product);
            console.log({
                proizvod_id: proizvod_id,
                kolicina_proizvoda: newQuantity,
                korpa_id: product.pivot.korpa_id
            });
            console.log('Response status:', response.status);
            const text = await response.text();  // Prvo pročitaj kao tekst
            console.log('Response body:', text);

            if (!response.ok) {
                console.error("Greška pri ažuriranju količine:", response.status, text);
                return;
            }

            const data = JSON.parse(text);  // Tek tada parsiraj ako je odgovor validan
            console.log("Uspešno ažurirano:", data);
            await fetchCart();
        } catch (error) {
            console.error("Greška pri ažuriranju količine proizvoda:", error);
        }
    };
    
    // Funkcija za uklanjanje proizvoda
    const removeProduct = async (proizvod_id) => {
        try {
            // Ažuriraj stanje korpe na front-endu
            setCart(prevCart => {
                const updatedCart = prevCart.filter(item => item.pivot.proizvod_id !== proizvod_id);
                console.log("Novo stanje korpe nakon uklanjanja proizvoda:", updatedCart);
                return updatedCart;
            });
    
            // Pošaljite zahtev serveru da uklonite proizvod iz baze
            const response = await fetch('http://localhost:8000/api/korpa/remove-product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({
                    proizvod_id: proizvod_id
                })
            });
    
            if (response.ok) {
                const data = await response.json();
                console.log("Uspešno uklonjen proizvod:", data);
                await fetchCart();  // Sinhronizuj sa serverom nakon lokalnih promena
            } else {
                const errorData = await response.json();  // Ako postoji telo greške
                console.error("Greška pri uklanjanju proizvoda:", errorData);
                alert("Došlo je do greške pri uklanjanju proizvoda. Pokušajte ponovo.");
                return;
            }
        } catch (error) {
            console.error("Greška pri uklanjanju proizvoda:", error);
        }
    };
    

    const clearCart = async () => {
        try {
            const response = await fetch('http://localhost:8000/api/korpa/clear', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json',
                }
            });
    
            if (response.ok) {
                console.log("Korpa uspešno obrisana!");
                setCart([]); // Resetuj stanje korpe na prazan niz
            } else {
                console.error("Greška pri brisanju korpe:", response);
            }
        } catch (error) {
            console.error("Greška pri brisanju korpe:", error);
        }
    };
    
    return (
        <CartContext.Provider value={{ cart, setCart, addToCart, fetchCart, updateQuantity, clearCart }}>
            {children}
        </CartContext.Provider>
    );
    
};
export const useCart = () => {
    return useContext(CartContext);
};