'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import { useAuth } from './auth-context';
import { getFavorites, toggleFavorite as apiToggleFavorite } from '@/lib/api';

export interface FavoriteItem {
  id: string;
  name: string;
  price: number;
  image: string;
  rating?: number;
}

interface FavoritesContextType {
  favorites: FavoriteItem[];
  addFavorite: (item: FavoriteItem) => Promise<void>;
  removeFavorite: (id: string) => Promise<void>;
  isFavorite: (id: string) => boolean;
}

const FavoritesContext = createContext<FavoritesContextType | undefined>(undefined);

export function FavoritesProvider({ children }: { children: React.ReactNode }) {
  const { user } = useAuth();
  const [favorites, setFavorites] = useState<FavoriteItem[]>([]);
  const [isLoaded, setIsLoaded] = useState(false);

  // Charger les favoris au démarrage
  useEffect(() => {
    async function loadFavorites() {
      if (user?.token) {
        // Charger depuis l'API si connecté
        try {
          const response = await getFavorites(user.token);
          if (response.success && response.data?.favoris) {
            const favoriteItems = response.data.favoris.map((item: any) => ({
              id: item.id_produit.toString(),
              name: item.nom,
              price: parseFloat(item.prix),
              image: item.image_url,
              rating: 4.5,
            }));
            setFavorites(favoriteItems);
          }
        } catch (error) {
          console.error('Error loading favorites from API:', error);
        }
      } else {
        // Charger depuis LocalStorage si non connecté
        const savedFavorites = localStorage.getItem('feminine-aura-favorites');
        if (savedFavorites) {
          setFavorites(JSON.parse(savedFavorites));
        }
      }
      setIsLoaded(true);
    }

    loadFavorites();
  }, [user]);

  // Sauvegarder dans LocalStorage si non connecté
  useEffect(() => {
    if (isLoaded && !user?.token) {
      localStorage.setItem('feminine-aura-favorites', JSON.stringify(favorites));
    }
  }, [favorites, isLoaded, user]);

  const addFavorite = async (newItem: FavoriteItem) => {
    if (user?.token) {
      // Utiliser l'API si connecté
      try {
        await apiToggleFavorite(user.token, parseInt(newItem.id));
        setFavorites((prevItems) => {
          const exists = prevItems.find((item) => item.id === newItem.id);
          if (exists) return prevItems;
          return [...prevItems, newItem];
        });
      } catch (error) {
        console.error('Error adding favorite:', error);
      }
    } else {
      // LocalStorage si non connecté
      setFavorites((prevItems) => {
        const exists = prevItems.find((item) => item.id === newItem.id);
        if (exists) return prevItems;
        return [...prevItems, newItem];
      });
    }
  };

  const removeFavorite = async (id: string) => {
    if (user?.token) {
      // Utiliser l'API si connecté
      try {
        await apiToggleFavorite(user.token, parseInt(id));
        setFavorites((prevItems) => prevItems.filter((item) => item.id !== id));
      } catch (error) {
        console.error('Error removing favorite:', error);
      }
    } else {
      // LocalStorage si non connecté
      setFavorites((prevItems) => prevItems.filter((item) => item.id !== id));
    }
  };

  const isFavorite = (id: string) => {
    return favorites.some((item) => item.id === id);
  };

  return (
    <FavoritesContext.Provider value={{ favorites, addFavorite, removeFavorite, isFavorite }}>
      {children}
    </FavoritesContext.Provider>
  );
}

export function useFavorites() {
  const context = useContext(FavoritesContext);
  if (!context) {
    throw new Error('useFavorites must be used within FavoritesProvider');
  }
  return context;
}
