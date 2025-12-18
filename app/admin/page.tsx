'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { Shield, Lock, Mail } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useAdmin } from '../context/admin-context';

export default function AdminLoginPage() {
  const router = useRouter();
  const { loginAdmin, isAdmin, loading } = useAdmin();

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    // Rediriger si déjà connecté en tant qu'admin
    if (!loading && isAdmin) {
      router.push('/admin/dashboard');
    }
  }, [isAdmin, loading, router]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setIsLoading(true);

    try {
      const success = await loginAdmin(email, password);

      if (success) {
        router.push('/admin/dashboard');
      } else {
        setError('Email ou mot de passe incorrect, ou accès non autorisé');
      }
    } catch (err: any) {
      setError(err.message || 'Erreur lors de la connexion');
    } finally {
      setIsLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 to-gray-800">
        <div className="text-white">Chargement...</div>
      </div>
    );
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-black px-4">
      <div className="w-full max-w-md">
        {/* Logo et Titre */}
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl mb-4 shadow-2xl">
            <Shield className="text-white" size={40} />
          </div>
          <h1 className="text-3xl font-bold text-white mb-2">
            Administration
          </h1>
          <p className="text-gray-400">Feminine Aura</p>
        </div>

        {/* Formulaire de connexion */}
        <div className="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-2xl p-8 shadow-2xl">
          <h2 className="text-xl font-semibold text-white mb-6 text-center">
            Connexion Sécurisée
          </h2>

          <form onSubmit={handleSubmit} className="space-y-5">
            {/* Email */}
            <div>
              <label className="block text-sm font-medium text-gray-300 mb-2">
                Adresse Email
              </label>
              <div className="relative">
                <Mail className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={20} />
                <Input
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="admin@feminineaura.com"
                  className="pl-11 bg-gray-900/50 border-gray-600 text-white placeholder:text-gray-500 focus:border-pink-500 h-12"
                  required
                />
              </div>
            </div>

            {/* Mot de passe */}
            <div>
              <label className="block text-sm font-medium text-gray-300 mb-2">
                Mot de passe
              </label>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={20} />
                <Input
                  type="password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="••••••••"
                  className="pl-11 bg-gray-900/50 border-gray-600 text-white placeholder:text-gray-500 focus:border-pink-500 h-12"
                  required
                />
              </div>
            </div>

            {/* Message d'erreur */}
            {error && (
              <div className="p-3 bg-red-500/10 border border-red-500/50 rounded-lg text-red-400 text-sm">
                {error}
              </div>
            )}

            {/* Bouton de connexion */}
            <Button
              type="submit"
              disabled={isLoading}
              className="w-full h-12 bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white font-semibold text-lg rounded-xl shadow-lg transition-all"
            >
              {isLoading ? 'Connexion...' : 'Se connecter'}
            </Button>
          </form>

          {/* Informations de test */}
          <div className="mt-6 p-4 bg-gray-900/50 rounded-lg border border-gray-700">
            <p className="text-xs text-gray-400 text-center mb-2">
              Compte de test :
            </p>
            <p className="text-xs text-gray-300 text-center font-mono">
              admin@feminineaura.com
            </p>
            <p className="text-xs text-gray-300 text-center font-mono">
              password123
            </p>
          </div>
        </div>

        {/* Retour au site */}
        <div className="mt-6 text-center">
          <a
            href="/"
            className="text-sm text-gray-400 hover:text-pink-400 transition-colors"
          >
            ← Retour au site
          </a>
        </div>
      </div>
    </div>
  );
}
