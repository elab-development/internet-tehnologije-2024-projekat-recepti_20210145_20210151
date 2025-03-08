import React, { useState } from "react";
import axios from "axios";
import { Link } from "react-router-dom";


const RecipeFinder = () => {
    const [ingredient, setIngredient] = useState("");
    const [ingredientsList, setIngredientsList] = useState([]);
    const [recipes, setRecipes] = useState([]);
    const [mealType, setMealType] = useState("");  // Novi state za tip jela

    // Dodavanje sastojka u listu
    const addIngredient = () => {
        if (ingredient.trim() !== "") {
            setIngredientsList([...ingredientsList, ingredient.trim()]);
            setIngredient(""); // Reset polja
        }
    };
    // Brisanje sastojka iz liste
    const removeIngredient = (index) => {
        const newList = ingredientsList.filter((_, i) => i !== index);
        setIngredientsList(newList);
    };

    // Pretraga recepata
    const searchRecipes = async () => {
        try {
            const response = await axios.post("http://localhost:8000/api/recipes/find", {
                ingredients: ingredientsList,
                tip_jela: mealType,  // Dodaj tip jela u telo zahteva
            });
            setRecipes(response.data);
        } catch (error) {
            console.error("Greška prilikom pretrage recepata:", error);
        }
    };

    return (
        <div className="recipe-finder-container">
            {/* Dropdown meni za tip jela */}
            <div className="find-dropdown">
                <label htmlFor="mealType">Izaberite tip jela:</label>
                <select
                    id="mealType"
                    value={mealType}
                    onChange={(e) => setMealType(e.target.value)}  // Postavljanje tipa jela
                >
                    <option value="">Izaberite tip jela</option>
                    <option value="predjelo">Predjelo</option>
                    <option value="glavno jelo">Glavno jelo</option>
                    <option value="dezert">Dezert</option>
                </select>
            </div>

            {/* Dodavanje sastojka */}
            <div className="input-group">
                <input
                    type="text"
                    value={ingredient}
                    onChange={(e) => setIngredient(e.target.value)}
                    placeholder="Unesite sastojak"
                />
                <button onClick={addIngredient}>Dodaj</button>
            </div>

            <ul className="ingredient-list">
                    {ingredientsList.map((item, index) => (
                        <li key={index} className="ingredient-item">
                            {item}
                            <button onClick={() => removeIngredient(index)} className="remove-btn">✖</button>
                        </li>
                    ))}
                </ul>

            {/* Pretraga recepata */}
            <button onClick={searchRecipes} className="find-btn">Pretraži recepte</button>

            {/* Prikaz recepata */}
            {recipes.length > 0 && (
                <div className="find-recipes-container">
                    <h2>Pronađeni recepti</h2>
                    {recipes.map((recipe) => (
                        <div key={recipe.id} className="find-recipe-card">
                            <h3>{recipe.naziv}</h3>
                            <img src={recipe.slika} alt={recipe.naziv} className="find-recipe-image" />
                            <p><strong>Poklapanje:</strong> {recipe.matchCount} sastojaka</p>
                            <Link to={`/recepti/${recipe.id}`} className="find-details-button">
                                Klikni za više detalja
                            </Link>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

export default RecipeFinder;
