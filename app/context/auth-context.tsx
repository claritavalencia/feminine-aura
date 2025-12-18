'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import { login as apiLogin, register as apiRegister, logout as apiLogout } from '@/lib/api';

export interface User {
  id: string;
  email: string;
  name: string;
  phone?: string;
  token?: string;
}

interface AuthContextType {
  user: User | null;
  isAuthenticated: boolean;
  login: (email: string, password: string) => Promise<void>;
  logout: () => Promise<void>;
  register: (email: string, password: string, name: string) => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [isLoaded, setIsLoaded] = useState(false);

  useEffect(() => {
    const savedUser = localStorage.getItem('feminine-aura-user');
    if (savedUser) {
      setUser(JSON.parse(savedUser));
    }
    setIsLoaded(true);
  }, []);

  const login = async (email: string, password: string) => {
    try {
      const response = await apiLogin({ email, mot_de_passe: password });

      if (response.success && response.data) {
        // L'API renvoie response.data.user (pas utilisateur)
        const userFromApi = response.data.user || response.data.utilisateur;
        const userData: User = {
          id: userFromApi.id_utilisatrice?.toString() || userFromApi.id?.toString(),
          email: userFromApi.email,
          name: userFromApi.nom || email.split('@')[0],
          phone: userFromApi.telephone,
          token: response.data.token,
        };
        setUser(userData);
        localStorage.setItem('feminine-aura-user', JSON.stringify(userData));
        localStorage.setItem('feminine-aura-token', response.data.token);
      } else {
        throw new Error(response.message || 'Erreur de connexion');
      }
    } catch (error) {
      console.error('Login failed:', error);
      throw error;
    }
  };

  const logout = async () => {
    try {
      const token = localStorage.getItem('feminine-aura-token');
      if (token) {
        await apiLogout();
      }
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      setUser(null);
      localStorage.removeItem('feminine-aura-user');
      localStorage.removeItem('feminine-aura-token');
    }
  };

  const register = async (email: string, password: string, name: string) => {
    try {
      const response = await apiRegister({ email, mot_de_passe: password });

      if (response.success && response.data) {
        // L'API renvoie response.data.user (pas utilisateur)
        const userFromApi = response.data.user || response.data.utilisateur;
        const userData: User = {
          id: userFromApi.id_utilisatrice?.toString() || userFromApi.id?.toString(),
          email: userFromApi.email,
          name: name || email.split('@')[0],
          token: response.data.token,
        };
        setUser(userData);
        localStorage.setItem('feminine-aura-user', JSON.stringify(userData));
        localStorage.setItem('feminine-aura-token', response.data.token);
      } else {
        throw new Error(response.message || 'Erreur lors de l\'inscription');
      }
    } catch (error) {
      console.error('Registration failed:', error);
      throw error;
    }
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        isAuthenticated: !!user,
        login,
        logout,
        register,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider');
  }
  return context;
}
