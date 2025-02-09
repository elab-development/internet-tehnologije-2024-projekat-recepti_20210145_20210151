

import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import OneProduct from './OneProduct';

const Products = () => {
    const [products, setProducts] = useState([]);

    useEffect(() => {
        let url = 'http://localhost:8000/api/proizvodi'; // Osnovni URL za API

        fetch(url) // Poziv Laravel API-ja
            .then(response => response.json())
            .then(data => {
                if (data.data) {
                    setProducts(data.data); // Postavljamo proizvode u state
                }
            })
            .catch(error => console.error('Error fetching products:', error));
    }, []);

    return (
        <div className="all-products">
            {products.length > 0 ? (
                products.map((product) => (
                    <OneProduct key={product.id} product={product} />
                ))
            ) : (
                <p>Nema dostupnih proizvoda.</p>
            )}
        </div>
    );
};

export default Products;

