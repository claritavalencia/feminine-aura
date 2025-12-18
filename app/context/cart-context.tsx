'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import { useAuth } from './auth-context';
import { getCart, addToCart as apiAddToCart, updateCartItem, removeFromCart, clearCart as apiClearCart } from '@/lib/api';

export interface CartItem {
  id: string;
  name: string;
  price: number;
  quantity: number;
  size?: string;
  color?: string;
  image: string;
}

interface CartContextType {
  items: CartItem[];
  addItem: (item: CartItem) => Promise<void>;
  removeItem: (id: string) => Promise<void>;
  updateQuantity: (id: string, quantity: number) => Promise<void>;
  clearCart: () => Promise<void>;
  total: number;
}

const CartContext = createContext<CartContextType | undefined>(undefined);

export function CartProvider({ children }: { children: React.ReactNode }) {
  const { user } = useAuth();
  const [items, setItems] = useState<CartItem[]>([]);
  const [isLoaded, setIsLoaded] = useState(false);

  // Charger le panier au démarrage
  useEffect(() => {
    async function loadCart() {
      if (user?.token) {
        // Charger depuis l'API si connecté
        try {
          const response = await getCart(user.token);
          if (response.success && response.data?.items) {
            const cartItems = response.data.items.map((item: any) => ({
              id: item.id_produit.toString(),
              name: item.nom,
              price: parseFloat(item.prix),
              quantity: item.quantite,
              image: item.image_url,
              size: item.taille,
              color: item.couleur,
            }));
            setItems(cartItems);
          }
        } catch (error) {
          console.error('Error loading cart from API:', error);
        }
      } else {
        // Charger depuis LocalStorage si non connecté
        const savedCart = localStorage.getItem('feminine-aura-cart');
        if (savedCart) {
          setItems(JSON.parse(savedCart));
        }
      }
      setIsLoaded(true);
    }

    loadCart();
  }, [user]);

  // Sauvegarder le panier dans LocalStorage si non connecté
  useEffect(() => {
    if (isLoaded && !user?.token) {
      localStorage.setItem('feminine-aura-cart', JSON.stringify(items));
    }
  }, [items, isLoaded, user]);

  const addItem = async (newItem: CartItem) => {
    if (user?.token) {
      // Utiliser l'API si connecté
      try {
        await apiAddToCart(user.token, parseInt(newItem.id), newItem.quantity);
        // Recharger le panier depuis l'API
        const response = await getCart(user.token);
        if (response.success && response.data?.items) {
          const cartItems = response.data.items.map((item: any) => ({
            id: item.id_produit.toString(),
            name: item.nom,
            price: parseFloat(item.prix),
            quantity: item.quantite,
            image: item.image_url,
            size: item.taille,
            color: item.couleur,
          }));
          setItems(cartItems);
        }
      } catch (error) {
        console.error('Error adding to cart:', error);
      }
    } else {
      // LocalStorage si non connecté
      setItems((prevItems) => {
        const existingItem = prevItems.find(
          (item) => item.id === newItem.id && item.size === newItem.size && item.color === newItem.color
        );

        if (existingItem) {
          return prevItems.map((item) =>
            item === existingItem
              ? { ...item, quantity: item.quantity + newItem.quantity }
              : item
          );
        }

        return [...prevItems, newItem];
      });
    }
  };

  const removeItem = async (id: string) => {
    if (user?.token) {
      // Utiliser l'API si connecté
      try {
        await removeFromCart(user.token, parseInt(id));
        setItems((prevItems) => prevItems.filter((item) => item.id !== id));
      } catch (error) {
        console.error('Error removing from cart:', error);
      }
    } else {
      // LocalStorage si non connecté
      setItems((prevItems) => prevItems.filter((item) => item.id !== id));
    }
  };

  const updateQuantity = async (id: string, quantity: number) => {
    if (quantity <= 0) {
      await removeItem(id);
      return;
    }

    if (user?.token) {
      // Utiliser l'API si connecté
      try {
        await updateCartItem(user.token, parseInt(id), quantity);
        setItems((prevItems) =>
          prevItems.map((item) =>
            item.id === id ? { ...item, quantity } : item
          )
        );
      } catch (error) {
        console.error('Error updating quantity:', error);
      }
    } else {
      // LocalStorage si non connecté
      setItems((prevItems) =>
        prevItems.map((item) =>
          item.id === id ? { ...item, quantity } : item
        )
      );
    }
  };

  const clearCart = async () => {
    if (user?.token) {
      // Utiliser l'API si connecté
      try {
        await apiClearCart(user.token);
        setItems([]);
      } catch (error) {
        console.error('Error clearing cart:', error);
      }
    } else {
      // LocalStorage si non connecté
      setItems([]);
    }
  };

  const total = items.reduce((sum, item) => sum + item.price * item.quantity, 0);

  return (
    <CartContext.Provider value={{ items, addItem, removeItem, updateQuantity, clearCart, total }}>
      {children}
    </CartContext.Provider>
  );
}

export function useCart() {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error('useCart must be used within CartProvider');
  }
  return context;
}
