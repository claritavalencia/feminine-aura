'use client';

import { useAuth } from '@/app/context/auth-context';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { User, Mail, Phone, MapPin, LogOut, Heart, ShoppingBag } from 'lucide-react';
import { useState } from 'react';

export default function ProfilePage() {
  const { user, isAuthenticated, logout } = useAuth();
  const router = useRouter();
  const [activeTab, setActiveTab] = useState('info');

  if (!isAuthenticated || !user) {
    return (
      <div className="min-h-screen bg-white py-20">
        <div className="max-w-md mx-auto text-center">
          <h1 className="text-3xl font-bold text-foreground mb-4">Connectez-vous</h1>
          <p className="text-gray-600 mb-8">Vous devez être connecté pour accéder à votre profil.</p>
          <Link href="/auth" className="btn-primary">
            Se connecter
          </Link>
        </div>
      </div>
    );
  }

  const handleLogout = () => {
    logout();
    router.push('/');
  };

  return (
    <div className="min-h-screen bg-gray-50 py-12">
      <div className="max-w-4xl mx-auto px-4">
        {/* Header */}
        <div className="bg-white rounded-lg shadow-sm p-8 mb-8">
          <div className="flex items-center justify-between mb-6">
            <div className="flex items-center gap-4">
              <div className="w-16 h-16 bg-gradient-to-br from-primary to-accent rounded-full flex items-center justify-center">
                <User className="w-8 h-8 text-white" />
              </div>
              <div>
                <h1 className="text-3xl font-bold text-foreground">{user.name}</h1>
                <p className="text-gray-600">{user.email}</p>
              </div>
            </div>
            <button
              onClick={handleLogout}
              className="flex items-center gap-2 text-red-600 hover:text-red-700 font-semibold transition-colors"
            >
              <LogOut size={20} />
              Déconnexion
            </button>
          </div>
        </div>

        {/* Navigation Tabs */}
        <div className="flex gap-4 mb-8 border-b border-gray-200">
          <button
            onClick={() => setActiveTab('info')}
            className={`pb-4 px-6 font-semibold transition-colors ${
              activeTab === 'info'
                ? 'text-primary border-b-2 border-primary'
                : 'text-gray-600 hover:text-foreground'
            }`}
          >
            Informations
          </button>
          <button
            onClick={() => setActiveTab('orders')}
            className={`pb-4 px-6 font-semibold transition-colors flex items-center gap-2 ${
              activeTab === 'orders'
                ? 'text-primary border-b-2 border-primary'
                : 'text-gray-600 hover:text-foreground'
            }`}
          >
            <ShoppingBag size={18} />
            Commandes
          </button>
          <button
            onClick={() => setActiveTab('favorites')}
            className={`pb-4 px-6 font-semibold transition-colors flex items-center gap-2 ${
              activeTab === 'favorites'
                ? 'text-primary border-b-2 border-primary'
                : 'text-gray-600 hover:text-foreground'
            }`}
          >
            <Heart size={18} />
            Favoris
          </button>
        </div>

        {/* Content */}
        <div className="bg-white rounded-lg shadow-sm p-8">
          {activeTab === 'info' && (
            <div>
              <h2 className="text-2xl font-bold text-foreground mb-6">Mes Informations</h2>
              <div className="space-y-6">
                <div className="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                  <Mail className="w-6 h-6 text-primary" />
                  <div>
                    <p className="text-sm text-gray-600">Email</p>
                    <p className="font-semibold text-foreground">{user.email}</p>
                  </div>
                </div>
                {user.phone && (
                  <div className="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <Phone className="w-6 h-6 text-primary" />
                    <div>
                      <p className="text-sm text-gray-600">Téléphone</p>
                      <p className="font-semibold text-foreground">{user.phone}</p>
                    </div>
                  </div>
                )}
                <div className="mt-8">
                  <button className="btn-primary">
                    Modifier mes informations
                  </button>
                </div>
              </div>
            </div>
          )}

          {activeTab === 'orders' && (
            <div>
              <h2 className="text-2xl font-bold text-foreground mb-6">Mes Commandes</h2>
              <div className="text-center py-12">
                <ShoppingBag className="w-16 h-16 mx-auto mb-4 text-gray-300" />
                <p className="text-gray-500 mb-4">Vous n'avez pas encore de commandes</p>
                <Link href="/boutique" className="text-primary font-semibold hover:opacity-70 transition-opacity">
                  Découvrez notre collection
                </Link>
              </div>
            </div>
          )}

          {activeTab === 'favorites' && (
            <div>
              <h2 className="text-2xl font-bold text-foreground mb-6">Mes Favoris</h2>
              <div className="text-center py-12">
                <Heart className="w-16 h-16 mx-auto mb-4 text-gray-300" />
                <p className="text-gray-500 mb-4">Vous n'avez pas encore de favoris</p>
                <Link href="/boutique" className="text-primary font-semibold hover:opacity-70 transition-opacity">
                  Ajouter des articles aux favoris
                </Link>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
