'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';

interface AdminUser {
  id: string;
  email: string;
  role: string;
  token: string;
}

interface AdminContextType {
  admin: AdminUser | null;
  isAdmin: boolean;
  loginAdmin: (email: string, password: string) => Promise<boolean>;
  logoutAdmin: () => void;
  loading: boolean;
}

const AdminContext = createContext<AdminContextType | undefined>(undefined);

export function AdminProvider({ children }: { children: React.ReactNode }) {
  const [admin, setAdmin] = useState<AdminUser | null>(null);
  const [loading, setLoading] = useState(true);
  const router = useRouter();

  useEffect(() => {
    // Vérifier si un admin est déjà connecté
    const savedAdmin = localStorage.getItem('feminine-aura-admin');
    if (savedAdmin) {
      try {
        const adminData = JSON.parse(savedAdmin);
        setAdmin(adminData);
      } catch (error) {
        localStorage.removeItem('feminine-aura-admin');
      }
    }
    setLoading(false);
  }, []);

  const loginAdmin = async (email: string, password: string): Promise<boolean> => {
    const API_URL = process.env.NEXT_PUBLIC_API_URL;

    // Validation des entrées
    if (!email || !password) {
      console.error('❌ Email et mot de passe requis');
      throw new Error('Email et mot de passe requis');
    }

    if (!API_URL) {
      console.error('❌ NEXT_PUBLIC_API_URL non défini dans .env.local');
      throw new Error('Configuration API manquante');
    }

    console.log('🔐 Tentative de connexion admin...', { email, API_URL });

    try {
      // Créer un contrôleur pour timeout
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 10000); // 10s timeout

      // Appel à l'API de connexion
      const response = await fetch(
        `${API_URL}/auth/login`,
        {
          method: 'POST',
          mode: 'cors',
          credentials: 'include',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            email,
            mot_de_passe: password,
          }),
          signal: controller.signal,
        }
      );

      clearTimeout(timeoutId);

      console.log('📡 Réponse API reçue:', {
        status: response.status,
        statusText: response.statusText,
        headers: Object.fromEntries(response.headers.entries()),
      });

      // Vérifier le statut HTTP
      if (!response.ok) {
        const errorText = await response.text();
        console.error('❌ Erreur HTTP:', { status: response.status, body: errorText });
        throw new Error(`Erreur HTTP ${response.status}: ${response.statusText}`);
      }

      // Parser la réponse JSON
      let data;
      try {
        data = await response.json();
        console.log('✅ Données JSON reçues:', data);
      } catch (parseError) {
        console.error('❌ Erreur parsing JSON:', parseError);
        throw new Error('Réponse API invalide (JSON attendu)');
      }

      // Vérifier le succès de la connexion
      if (!data.success) {
        console.error('❌ Connexion refusée:', data.message);
        throw new Error(data.message || 'Identifiants incorrects');
      }

      if (!data.data) {
        console.error('❌ Données utilisateur manquantes');
        throw new Error('Réponse API invalide (données manquantes)');
      }

      const userFromApi = data.data.user || data.data.utilisateur;

      if (!userFromApi) {
        console.error('❌ Objet utilisateur manquant');
        throw new Error('Réponse API invalide (utilisateur manquant)');
      }

      // Vérifier que l'utilisateur est un admin
      if (userFromApi.role !== 'admin') {
        console.error('❌ Rôle non autorisé:', userFromApi.role);
        throw new Error('Accès non autorisé. Seuls les administrateurs peuvent se connecter.');
      }

      // Créer l'objet admin
      const adminData: AdminUser = {
        id: userFromApi.id_utilisatrice?.toString() || userFromApi.id?.toString(),
        email: userFromApi.email,
        role: userFromApi.role,
        token: data.data.token,
      };

      console.log('✅ Connexion admin réussie:', { id: adminData.id, email: adminData.email });

      setAdmin(adminData);
      localStorage.setItem('feminine-aura-admin', JSON.stringify(adminData));

      return true;

    } catch (error: any) {
      // Gestion des erreurs spécifiques
      if (error.name === 'AbortError') {
        console.error('❌ Timeout: L\'API ne répond pas (>10s)');
        throw new Error('Timeout: Le serveur ne répond pas. Vérifiez que XAMPP est démarré.');
      }

      if (error.message?.includes('Failed to fetch')) {
        console.error('❌ Impossible de contacter l\'API');
        console.error('Vérifications nécessaires:');
        console.error('1. XAMPP est démarré (Apache + MySQL)');
        console.error('2. API accessible sur:', API_URL);
        console.error('3. Pas de blocage CORS');
        throw new Error('Impossible de contacter le serveur. Vérifiez que XAMPP est démarré.');
      }

      // Autres erreurs
      console.error('❌ Erreur de connexion:', error);
      throw error;
    }
  };

  const logoutAdmin = () => {
    setAdmin(null);
    localStorage.removeItem('feminine-aura-admin');
    router.push('/admin');
  };

  return (
    <AdminContext.Provider
      value={{
        admin,
        isAdmin: !!admin && admin.role === 'admin',
        loginAdmin,
        logoutAdmin,
        loading,
      }}
    >
      {children}
    </AdminContext.Provider>
  );
}

export function useAdmin() {
  const context = useContext(AdminContext);
  if (!context) {
    throw new Error('useAdmin must be used within AdminProvider');
  }
  return context;
}
