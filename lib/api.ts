/**
 * Client API pour communiquer avec le backend PHP
 * Base URL: http://localhost/Feminine Aura_last/api
 */

const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost/Feminine%20Aura_last/api';

// Types
export interface Product {
  id_produit: number;
  id_categorie: number;
  nom: string;
  description: string;
  prix: string;
  stock: number;
  couleur: string;
  taille: string;
  tissu: string;
  image_url: string;
  actif: number;
  date_creation: string;
  date_modification: string;
  categorie_nom?: string;
  categorie_slug?: string;
}

export interface Category {
  id_categorie: number;
  nom: string;
  description: string;
  slug: string;
  date_creation: string;
}

export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data?: T;
}

// Helper pour les requêtes
async function apiRequest<T>(
  endpoint: string,
  options: RequestInit = {}
): Promise<ApiResponse<T>> {
  const url = `${API_BASE_URL}/${endpoint}`;

  console.log('🔵 API Request:', url);

  const defaultHeaders = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  };

  const config: RequestInit = {
    ...options,
    mode: 'cors',
    credentials: 'include',
    headers: {
      ...defaultHeaders,
      ...options.headers,
    },
  };

  try {
    const response = await fetch(url, config);
    console.log('🟢 API Response status:', response.status);

    if (!response.ok) {
      console.error('❌ HTTP Error:', response.status, response.statusText);
      return {
        success: false,
        message: `Erreur HTTP ${response.status}: ${response.statusText}`,
      };
    }

    const data = await response.json();
    console.log('📦 API Data:', data);
    return data;
  } catch (error: any) {
    console.error('🔴 API Request Error:', error);
    console.error('URL was:', url);

    // Gestion d'erreurs spécifiques
    if (error.name === 'AbortError') {
      return {
        success: false,
        message: 'Timeout: Le serveur ne répond pas',
      };
    }

    if (error.message?.includes('Failed to fetch')) {
      console.error('❌ Impossible de contacter l\'API');
      console.error('Vérifications:');
      console.error('1. XAMPP Apache est démarré');
      console.error('2. API accessible sur:', API_BASE_URL);
      return {
        success: false,
        message: 'Impossible de contacter le serveur. Vérifiez que XAMPP est démarré.',
      };
    }

    return {
      success: false,
      message: 'Erreur de connexion à l\'API',
    };
  }
}

// === PRODUITS ===

export async function getProducts(): Promise<ApiResponse<{ produits: Product[]; pagination: any }>> {
  return apiRequest('produits');
}

export async function getProduct(id: number): Promise<ApiResponse<Product>> {
  return apiRequest(`produits/${id}`);
}

export async function getProductsByCategory(categoryId: number): Promise<ApiResponse<{ produits: Product[] }>> {
  return apiRequest(`produits?categorie=${categoryId}`);
}

// === CATEGORIES ===

export async function getCategories(): Promise<ApiResponse<{ categories: Category[] }>> {
  return apiRequest('categories');
}

export async function getCategory(id: number): Promise<ApiResponse<Category>> {
  return apiRequest(`categories/${id}`);
}

// === AUTHENTIFICATION ===

export interface LoginData {
  email: string;
  mot_de_passe: string;
}

export interface RegisterData {
  email: string;
  mot_de_passe: string;
}

export async function login(data: LoginData): Promise<ApiResponse<any>> {
  return apiRequest('auth/login', {
    method: 'POST',
    body: JSON.stringify(data),
  });
}

export async function register(data: RegisterData): Promise<ApiResponse<any>> {
  return apiRequest('auth/register', {
    method: 'POST',
    body: JSON.stringify(data),
  });
}

export async function logout(): Promise<ApiResponse<any>> {
  return apiRequest('auth/logout', {
    method: 'POST',
  });
}

export async function getMe(token: string): Promise<ApiResponse<any>> {
  return apiRequest('auth/me', {
    headers: {
      'Authorization': `Bearer ${token}`,
    },
  });
}

// === PANIER ===

export async function getCart(token: string): Promise<ApiResponse<any>> {
  return apiRequest('panier', {
    headers: {
      'Authorization': `Bearer ${token}`,
    },
  });
}

export async function addToCart(token: string, productId: number, quantity: number): Promise<ApiResponse<any>> {
  return apiRequest('panier/add', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify({
      id_produit: productId,
      quantite: quantity,
    }),
  });
}

export async function updateCartItem(token: string, productId: number, quantity: number): Promise<ApiResponse<any>> {
  return apiRequest('panier/update', {
    method: 'PUT',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify({
      id_produit: productId,
      quantite: quantity,
    }),
  });
}

export async function removeFromCart(token: string, productId: number): Promise<ApiResponse<any>> {
  return apiRequest('panier/remove', {
    method: 'DELETE',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify({
      id_produit: productId,
    }),
  });
}

export async function clearCart(token: string): Promise<ApiResponse<any>> {
  return apiRequest('panier/clear', {
    method: 'DELETE',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
  });
}

// === FAVORIS ===

export async function getFavorites(token: string): Promise<ApiResponse<any>> {
  return apiRequest('favoris', {
    headers: {
      'Authorization': `Bearer ${token}`,
    },
  });
}

export async function addFavorite(token: string, productId: number): Promise<ApiResponse<any>> {
  return apiRequest('favoris', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify({
      id_produit: productId,
    }),
  });
}

export async function toggleFavorite(token: string, productId: number): Promise<ApiResponse<any>> {
  return apiRequest('favoris/toggle', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify({
      id_produit: productId,
    }),
  });
}

export async function removeFavorite(token: string, productId: number): Promise<ApiResponse<any>> {
  return apiRequest('favoris', {
    method: 'DELETE',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify({
      id_produit: productId,
    }),
  });
}

// === COMMANDES ===

export async function getOrders(token: string): Promise<ApiResponse<any>> {
  return apiRequest('commandes', {
    headers: {
      'Authorization': `Bearer ${token}`,
    },
  });
}

export async function getOrder(token: string, id: number): Promise<ApiResponse<any>> {
  return apiRequest(`commandes/${id}`, {
    headers: {
      'Authorization': `Bearer ${token}`,
    },
  });
}

export async function createOrder(token: string, orderData: any): Promise<ApiResponse<any>> {
  return apiRequest('commandes', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify(orderData),
  });
}
