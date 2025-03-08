import React, { useEffect, useState, useCallback } from 'react';
import { useLocation } from 'react-router-dom'; // Za uzimanje parametara iz URL-a
import OneProduct from './OneProduct';

const SearchProducts = () => {
    const location = useLocation(); // Koristi useLocation za pristup URL-u
    const [products, setProducts] = useState([]);
    const [pagination, setPagination] = useState({});
    const [currentPage, setCurrentPage] = useState(1);
    const [keyword, setKeyword] = useState('');
    const [type, setType] = useState('');
    const [minPrice, setMinPrice] = useState('');
    const [maxPrice, setMaxPrice] = useState('');
    const [inStock, setInStock] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');

    // Funkcija za fetch proizvoda sa odgovarajuce stranice
    const fetchProducts = useCallback(async (page = 1) => {
        try {
            const queryParams = new URLSearchParams(location.search);
            const category = queryParams.get('kategorija');  // Pretrazujemo proizvode po kategoriji iz URL-a

            // Slanje zahteva sa svim parametrima (kategorija + filtriranje)
            const url = `http://localhost:8000/api/proizvodi/pretraga?kategorija=${category}&keyword=${keyword}&tip=${type}&cena_min=${minPrice}&cena_max=${maxPrice}&dostupna_kolicina=${inStock ? 1 : 0}&page=${page}`;
            const response = await fetch(url);

            if (response.status === 404) {
                setErrorMessage("Nema proizvoda koji zadovoljavaju vaše kriterijume.");
                setProducts([]);
            } else {
                const data = await response.json();
                if (data.data) {
                    setProducts(data.data);
                    setPagination(data.pagination);
                    setErrorMessage('');
                } else {
                    setErrorMessage("Nema proizvoda koji zadovoljavaju vaše kriterijume.");
                }
            }
        } catch (error) {
            console.error("Došlo je do greške:", error);
            setErrorMessage("Došlo je do greške prilikom pretrage.");
        }
    }, [keyword, type, minPrice, maxPrice, inStock, location.search]);
    // useEffect za inicijalni fetch proizvoda
    useEffect(() => {
        fetchProducts(currentPage);
    }, [currentPage, fetchProducts]);
    const handlePrevPage = () => {
        if (pagination.current_page > 1) {
            setCurrentPage(pagination.current_page - 1);
        }
    };
    const handleNextPage = () => {
        if (pagination.current_page < pagination.last_page) {
            setCurrentPage(pagination.current_page + 1);
        }
    };

    return (
        <div>
            {/* Pretraga */}
            <div className="search-container">
                <input 
                    type="text" 
                    placeholder="Pretraži proizvode..." 
                    value={keyword} 
                    onChange={(e) => setKeyword(e.target.value)} 
                    className="search-input"
                />
            </div>

            {/* Filtriranje */}
            <div className="filter-container">
                <select 
                    className="filter-select" 
                    value={type} 
                    onChange={(e) => setType(e.target.value)}
                >
                    <option value="">Tip</option>
                    <option value="organski">Organski</option>
                    <option value="neorganski">Neorganski</option>
                </select>
                <input 
                    type="number" 
                    placeholder="Min. cena" 
                    value={minPrice} 
                    onChange={(e) => setMinPrice(e.target.value)} 
                    className="filter-input"
                />
                <input 
                    type="number" 
                    placeholder="Max. cena" 
                    value={maxPrice} 
                    onChange={(e) => setMaxPrice(e.target.value)} 
                    className="filter-input"
                />
                <label className="in-stock-label">
                    Dostupno
                    <input 
                        type="checkbox" 
                        checked={inStock} 
                        onChange={(e) => setInStock(e.target.checked)} 
                        className="in-stock-checkbox"
                    />
                </label>
            </div>

            {/* Prikaz proizvoda */}
            <div className="all-products">
                {errorMessage ? (
                    <p>{errorMessage}</p>  // Prikazujemo poruku o grešci ako postoji
                ) : (
                    products.length > 0 ? (
                        products.map(product => (
                            <OneProduct key={product.id} product={product} />
                        ))
                    ) : (
                        <p>Nema proizvoda.</p>
                    )
                )}
            </div>

            {/* Paginacija */}
            <div className="pagination">
                {pagination.current_page > 1 && (
                    <button onClick={handlePrevPage}>Prethodna</button>
                )}

                <span>{pagination.current_page} / {pagination.last_page}</span>

                {pagination.current_page < pagination.last_page && (
                    <button onClick={handleNextPage}>Sledeća</button>
                )}
            </div>
        </div>
    );
};

export default SearchProducts;
