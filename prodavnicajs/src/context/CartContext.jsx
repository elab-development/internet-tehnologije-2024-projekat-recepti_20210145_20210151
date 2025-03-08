import React, { createContext, useState, useEffect, useContext } from 'react';

const CartContext = createContext();

export { CartContext };


export const CartProvider = ({ children }) => {

    const [cart, setCart] = useState([]);

    useEffect(() => {
        console.log("Korpa ažurirana:", cart);
    }, [cart]);

    /*const fetchCart = async () => {
        try {
            const response = await fetch('http://localhost:8000/api/korpa', {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}`, }
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
    };*/

    /*const addToCart = async (product) => {
        try {
            console.log("Proizvod koji se dodaje u korpu", product);
        
                
            const token = localStorage.getItem('token');
            console.log(token);
            if (!token) {
                console.error("Nema tokena, korisnik nije ulogovan!");
                alert("Morate biti prijavljeni da biste mogli uspešno obaviti kupovinu!");
                return;
            }
        
            console.log("product.id:", product.id);
            console.log("cart:", cart);
            // Provera da li proizvod već postoji u korpi
            const existingProduct = cart.find(item => item.pivot.proizvod_id === product.id);
            console.log("Postojeći proizvod u korpi:", existingProduct);  // Dodaj log za proizvod koji je pronađen

            let quantity = 1;  // Početna količina koja se dodaje ili menja
            if (existingProduct) {
                // Ako proizvod već postoji u korpi, uzmi trenutnu količinu
                quantity = existingProduct.pivot.kolicina_proizvoda + 1; // Povećaj količinu za 1
                await updateQuantity(product.id, "increase");  // Ažuriranje količine
                alert("Količina proizvoda je povećana.");
            }else{
                // Ako proizvod ne postoji, dodaješ ga u korpu
                const response = await fetch('http://localhost:8000/api/korpa/add-product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify({
                    proizvod_id: product.id,
                    kolicina: quantity,
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
    };*/
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
        } catch (error) {
            console.error("Greška pri dodavanju proizvoda:", error);
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

            let newQuantity = action === "increase"
                ? product.pivot.kolicina_proizvoda + 1
                : product.pivot.kolicina_proizvoda - 1;

            if (newQuantity < 1) {
                //console.warn("Količina ne može biti manja od 1.");
                //return;
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
                    kolicina: newQuantity,
                    korpa_id: product.pivot.korpa_id
                })
            });

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
    };

    // Funkcija za uklanjanje proizvoda
    const removeProduct = async (proizvod_id) => {
        try {
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
                console.log("Proizvod uspešno uklonjen:", data);
                await fetchCart();  // Sinhronizuj sa serverom nakon uklanjanja proizvoda
            } else {
                console.error("Greška pri uklanjanju proizvoda:", response);
            }
        } catch (error) {
            console.error("Greška pri pozivu funkcije za uklanjanje proizvoda:", error);
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
