'use client';

import { useFavorites } from '@/app/context/favorites-context';
import Link from 'next/link';
import { Heart, ShoppingCart, Trash2 } from 'lucide-react';

export default function FavoritesPage() {
  const { favorites, removeFavorite } = useFavorites();

  return (
    <div className="min-h-screen bg-gray-50 py-12">
      <div className="max-w-6xl mx-auto px-4">
        <h1 className="text-4xl font-bold text-foreground mb-2">Mes Favoris</h1>
        <p className="text-gray-600 mb-8">{favorites.length} article(s) sauvegardé(s)</p>

        {favorites.length === 0 ? (
          <div className="bg-white rounded-lg shadow-sm p-12 text-center">
            <Heart className="w-16 h-16 mx-auto mb-4 text-gray-300" />
            <h2 className="text-2xl font-bold text-gray-600 mb-4">Pas de favoris</h2>
            <p className="text-gray-500 mb-8">Commencez à ajouter vos articles préférés à votre liste de favoris!</p>
            <Link href="/boutique" className="btn-primary">
              Découvrir la collection
            </Link>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {favorites.map((item) => (
              <div key={item.id} className="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <div className="relative h-64 bg-gray-200 overflow-hidden">
                  <img
                    src={item.image || '/placeholder.svg'}
                    alt={item.name}
                    className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                  />
                  <button
                    onClick={() => removeFavorite(item.id)}
                    className="absolute top-3 right-3 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors"
                    aria-label="Supprimer des favoris"
                  >
                    <Trash2 size={18} />
                  </button>
                </div>

                <div className="p-4">
                  <h3 className="font-semibold text-foreground mb-2 line-clamp-2">{item.name}</h3>
                  
                  <div className="flex items-center justify-between mb-4">
                    <p className="text-xl font-bold text-primary">{item.price.toFixed(2)}€</p>
                    {item.rating && (
                      <p className="text-yellow-400 text-sm">
                        {'★'.repeat(Math.floor(item.rating))}
                      </p>
                    )}
                  </div>

                  <div className="flex gap-3">
                    <button className="flex-1 btn-primary flex items-center justify-center gap-2">
                      <ShoppingCart size={18} />
                      Ajouter
                    </button>
                    <Link
                      href={`/product/${item.id}`}
                      className="flex-1 btn-secondary flex items-center justify-center"
                    >
                      Voir
                    </Link>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
