import React, { useEffect, useState } from 'react';
import OneProduct from './OneProduct';

const Products = () => {
    const [products, setProducts] = useState([]);
    const [pagination, setPagination] = useState({});
    const [currentPage, setCurrentPage] = useState(1);

    // Funkcija za fetch proizvoda sa odgovarajuće stranice
    const fetchProducts = async (page = 1) => {
        const response = await fetch(`http://localhost:8000/api/proizvodi?page=${page}`);
        const data = await response.json();

        if (data.data) {
            setProducts(data.data);
            setPagination(data.pagination);
        }
    };

    useEffect(() => {
        fetchProducts(currentPage); // Pozivamo fetch za početnu stranicu
    }, [currentPage]);

    return (
        <div>
            <div className="all-products">
                {products.length > 0 ? (
                    products.map(product => (
                        <OneProduct key={product.id} product={product} />
                    ))
                ) : (
                    <p>Nema proizvoda.</p>
                )}
            </div>

            
            <div className="pagination">
                
                {pagination.current_page > 1 && (
                    <button onClick={() => setCurrentPage(pagination.current_page - 1)}>Prethodna</button>
                )}

                
                <span>{pagination.current_page} / {pagination.last_page}</span>

                
                {pagination.current_page < pagination.last_page && (
                    <button onClick={() => setCurrentPage(pagination.current_page + 1)}>Sledeća</button>
                )}
            </div>
        </div>
    );
};

export default Products;

