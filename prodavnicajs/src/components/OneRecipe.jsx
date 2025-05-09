import { FaClock } from 'react-icons/fa';
import { MdAddShoppingCart } from "react-icons/md";
import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { useCart } from '../context/CartContext';
import React from 'react';


const OneRecipe = () => {
  const { id } = useParams();
  const { addToCart } = useCart(); // Uzimamo funkciju za dodavanje u korpu iz konteksta
  const [recipe, setRecipe] = useState(null);
  const [products, setProducts] = useState([]);
  const [selectedProduct, setSelectedProduct] = useState(null); // Proizvod koji se dodaje
  const [showModal, setShowModal] = useState(false); // Prikaz modala

  useEffect(() => {
    fetch(`http://localhost:8000/api/recepti/${id}`)
      .then((response) => response.json())
      .then((data) => {
        if (!data || typeof data !== "object" || !data.proizvodi) {
          console.error("Nevalidan odgovor API-ja!", data);
          return;
        }
        setRecipe(data);
        setProducts(data.proizvodi || []);
      })
      .catch((error) => console.error("Greška pri učitavanju recepta:", error));
  }, [id]);

  const handleAddToCart = (product) => {
    setSelectedProduct(product); // Postavljamo proizvod koji želimo da dodamo
    setShowModal(true); // Otvaramo modal
  };

  const confirmAddToCart = () => {
    if (selectedProduct) {
      addToCart(selectedProduct); // Pozivamo funkciju za dodavanje u korpu
      setShowModal(false); // Zatvaramo modal
      setSelectedProduct(null);
    }
  };

  if (!recipe) return <p className="loading">Učitavanje...</p>;

  return (
    <div className="one-recipe-container">
      <img className="one-recipe-image" src={recipe.slika} alt="Recept" />
      <div className="one-recipe-content">
        <h2 className="one-recipe-title">{recipe.naziv}</h2>
        <p className="one-recipe-type">{recipe.tip_jela}</p>
        <p className="one-recipe-time">
          <FaClock className="time-icon" /> {recipe.vreme_pripreme} min
        </p>

        {/* Sekcija za proizvode */}
        <h3 className="one-recipe-subtitle">Potrebni proizvodi:</h3>
        <ul className="products-list">
          {products.map((product) => (
            <li key={product.id} className="product-item">
              <div className="product-info">
                <span className="product-name">{product.naziv}</span>
                <span className="product-quantity">
                  {product.pivot.kolicina} {product.pivot.merna_jedinica}
                </span>
              </div>
              {/* Ikonica za dodavanje u korpu */}
              <MdAddShoppingCart
                className="add-to-cart-icon"
                onClick={() => handleAddToCart(product)}
                role="button"  // Dodajemo role button
                aria-label="Dodaj u korpu"
              />
            </li>
          ))}
        </ul>

        <h3 className="one-recipe-subtitle">Opis pripreme:</h3>
        <p className="one-recipe-description">{recipe.opis_pripreme}</p>
      </div>

      {/* Modal za potvrdu dodavanja u korpu */}
      {showModal && (
        <div className="modal-overlay">
        <div className="modal-content">
          <img src={selectedProduct.slika} alt="Proizvod" className="product-image" />
          <p>Da li ste sigurni da želite da dodate ovaj proizvod u korpu?</p>
          <div className="modal-buttons">
            <button className="yes-button" onClick={confirmAddToCart}>Da</button>
            <button className="no-button" onClick={() => setShowModal(false)}>Ne</button>
          </div>
        </div>
      </div>
      )}
    </div>
  );
};

export default OneRecipe;
