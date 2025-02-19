


import React, { useEffect, useState } from 'react';
import { useLocation } from 'react-router-dom'; // Za uzimanje parametara iz URL-a
import OneProduct from './OneProduct';

const SearchProducts = () => {
    const location = useLocation(); // Koristi useLocation za pristup URL-u
    const [products, setProducts] = useState([]);
    const [pagination, setPagination] = useState({}); // State za paginaciju
    const [currentPage, setCurrentPage] = useState(1); // Trenutna stranica

    useEffect(() => {
        const fetchProducts = async (page = 1) => {
            const queryParams = new URLSearchParams(location.search);
            const category = queryParams.get('kategorija');
            const url = `http://localhost:8000/api/proizvodi/pretraga?kategorija=${category}&page=${page}`;

            console.log('Poslati url:', url);  // Ispisivanje URL-a za debug

            const response = await fetch(url);
            const data = await response.json();

            if (data.data) {
                setProducts(data.data);
                setPagination(data.pagination || {}); // Spremi podatke o paginaciji
            }
        };

        fetchProducts(currentPage); // Pozivamo fetch za trenutnu stranicu
    }, [location, currentPage]); // useEffect zavisi od location i currentPage

    // Funkcija za promenu stranice
    const handlePageChange = (page) => {
        if (page < 1 || page > pagination.last_page) return; // Proveravamo da li je stranica unutar granica
        setCurrentPage(page);
    };

    return (
        <div>
            <div className="all-products">
                {products.length > 0 ? (
                    products.map((product) => (
                        <OneProduct key={product.id} product={product} />
                    ))
                ) : (
                    <p>Nema dostupnih proizvoda za ovu kategoriju.</p>
                )}
            </div>

            {/* Paginacija */}
            {pagination.total > 0 && (
                <div className="pagination">
                    {/* Dugme za prethodnu stranicu */}
                    {pagination.current_page > 1 && (
                        <button onClick={() => handlePageChange(pagination.current_page - 1)}>
                            Prethodna
                        </button>
                    )}

                    {/* Prikaz trenutne stranice */}
                    <span>{pagination.current_page} / {pagination.last_page}</span>

                    {/* Dugme za sledeću stranicu */}
                    {pagination.current_page < pagination.last_page && (
                        <button onClick={() => handlePageChange(pagination.current_page + 1)}>
                            Sledeća
                        </button>
                    )}
                </div>
            )}
        </div>
    );
};

export default SearchProducts;


