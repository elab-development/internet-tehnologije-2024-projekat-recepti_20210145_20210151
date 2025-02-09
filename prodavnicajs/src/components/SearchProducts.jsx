

import React, { useEffect, useState } from 'react';
import { useLocation } from 'react-router-dom'; // Za uzimanje parametara iz URL-a
import OneProduct from './OneProduct';

const SearchProducts = () => {
    const location = useLocation(); // Koristi useLocation za pristup URL-u
    const [products, setProducts] = useState([]);

    useEffect(() => {
        const queryParams = new URLSearchParams(location.search);
        const category = queryParams.get('kategorija');
        
        console.log('Kategorija:', category);

        const url = `http://localhost:8000/api/proizvodi/pretraga?kategorija=${category}`;
        console.log('Poslati url:', url);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log("Podaci sa API-a:", data);
                if (data.data && Array.isArray(data.data.data)) {
                    setProducts(data.data.data);
                } else {
                    setProducts([]);
                }
            })
            .catch(error => console.error('Error fetching products:', error));
    }, [location]);

    return (
        <div className="all-products">
            {products.length > 0 ? (
                products.map((product) => (
                    <OneProduct key={product.id} product={product} />
                ))
            ) : (
                <p>Nema dostupnih proizvoda za ovu kategoriju.</p>
            )}
        </div>
    );
};

export default SearchProducts;


