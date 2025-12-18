'use client';

import { useState, useEffect } from 'react';
import { useParams } from 'next/navigation';
import Image from 'next/image';
import Link from 'next/link';
import { Heart, Star, ShoppingCart, Truck, Shield, Award, ChevronLeft, ChevronRight } from 'lucide-react';
import { getProduct, getProducts, Product } from '@/lib/api';
import { useCart } from '@/app/context/cart-context';
import { useFavorites } from '@/app/context/favorites-context';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

interface Review {
  id: number;
  author: string;
  rating: number;
  date: string;
  comment: string;
  verified: boolean;
}

export default function ProductPage() {
  const params = useParams();
  const productId = params?.id as string;

  const { addItem } = useCart();
  const { addFavorite, removeFavorite, isFavorite: checkIsFavorite } = useFavorites();

  const [product, setProduct] = useState<any>(null);
  const [similarProducts, setSimilarProducts] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [isFavorite, setIsFavorite] = useState(false);
  const [selectedImage, setSelectedImage] = useState(0);
  const [selectedSize, setSelectedSize] = useState('');
  const [selectedColor, setSelectedColor] = useState('');
  const [quantity, setQuantity] = useState(1);
  const [addedToCart, setAddedToCart] = useState(false);

  // Mock data pour les avis (à remplacer par API)
  const [reviews] = useState<Review[]>([
    {
      id: 1,
      author: 'Sophie M.',
      rating: 5,
      date: '2024-12-01',
      comment: 'Qualité exceptionnelle ! Le tissu est doux et confortable. Je recommande vivement.',
      verified: true
    },
    {
      id: 2,
      author: 'Marie L.',
      rating: 4,
      date: '2024-11-28',
      comment: 'Très beau produit, conforme à la description. La taille correspond parfaitement.',
      verified: true
    },
    {
      id: 3,
      author: 'Camille D.',
      rating: 5,
      date: '2024-11-25',
      comment: 'Magnifique ! Je suis ravie de mon achat. La livraison était rapide.',
      verified: false
    }
  ]);

  // Simulated image gallery (en production, récupérer depuis l'API)
  const [images, setImages] = useState<string[]>([]);

  useEffect(() => {
    async function loadProduct() {
      if (!productId) return;

      try {
        setLoading(true);
        const response = await getProduct(parseInt(productId));

        if (response.success && response.data) {
          setProduct(response.data);
          setSelectedSize(response.data.taille || '');
          setSelectedColor(response.data.couleur || '');

          // Simuler une galerie d'images (en prod, venir de l'API)
          setImages([
            response.data.image_url,
            response.data.image_url,
            response.data.image_url,
          ]);
        }

        // Charger des produits similaires
        const productsResponse = await getProducts();
        if (productsResponse.success && productsResponse.data?.produits) {
          const similar = productsResponse.data.produits
            .filter((p: Product) =>
              p.id_produit !== parseInt(productId) &&
              p.id_categorie === response.data?.id_categorie
            )
            .slice(0, 4);
          setSimilarProducts(similar);
        }
      } catch (error) {
        console.error('Error loading product:', error);
      } finally {
        setLoading(false);
      }
    }

    loadProduct();
  }, [productId]);

  useEffect(() => {
    if (productId) {
      setIsFavorite(checkIsFavorite(productId));
    }
  }, [productId, checkIsFavorite]);

  if (loading) {
    return (
      <div className="min-h-screen bg-white flex items-center justify-center">
        <div className="text-center">
          <div className="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-pink-500 border-r-transparent"></div>
          <p className="mt-4 text-lg text-gray-600">Chargement du produit...</p>
        </div>
      </div>
    );
  }

  if (!product) {
    return (
      <div className="min-h-screen bg-white flex items-center justify-center">
        <div className="text-center">
          <p className="text-xl text-gray-600 mb-4">Produit non trouvé</p>
          <Link href="/boutique" className="text-pink-500 hover:underline">
            Retour à la boutique
          </Link>
        </div>
      </div>
    );
  }

  const handleAddToCart = async () => {
    if (!product) return;

    try {
      await addItem({
        id: product.id_produit.toString(),
        name: product.nom,
        price: parseFloat(product.prix),
        quantity: quantity,
        image: product.image_url,
        size: selectedSize,
        color: selectedColor,
      });

      setAddedToCart(true);
      setTimeout(() => setAddedToCart(false), 3000);
    } catch (error) {
      console.error('Error adding product to cart:', error);
    }
  };

  const handleToggleFavorite = async () => {
    if (!product) return;

    try {
      if (isFavorite) {
        await removeFavorite(product.id_produit.toString());
      } else {
        await addFavorite({
          id: product.id_produit.toString(),
          name: product.nom,
          price: parseFloat(product.prix),
          image: product.image_url,
          rating: 4.5,
        });
      }
      setIsFavorite(!isFavorite);
    } catch (error) {
      console.error('Error toggling favorite:', error);
    }
  };

  const renderStars = (rating: number, size: 'sm' | 'md' = 'md') => {
    const starSize = size === 'sm' ? 14 : 18;
    const stars = [];
    for (let i = 1; i <= 5; i++) {
      stars.push(
        <Star
          key={i}
          size={starSize}
          className={i <= rating ? 'fill-amber-400 text-amber-400' : 'text-gray-300'}
        />
      );
    }
    return stars;
  };

  const averageRating = reviews.reduce((acc, r) => acc + r.rating, 0) / reviews.length;

  return (
    <div className="min-h-screen bg-white">
      {/* Breadcrumb */}
      <div className="max-w-7xl mx-auto px-4 py-4">
        <nav className="text-sm text-gray-500">
          <Link href="/" className="hover:text-pink-500">Accueil</Link>
          <span className="mx-2">/</span>
          <Link href="/boutique" className="hover:text-pink-500">Boutique</Link>
          <span className="mx-2">/</span>
          <span className="text-gray-900">{product.nom}</span>
        </nav>
      </div>

      {/* Product Section */}
      <div className="max-w-7xl mx-auto px-4 py-8">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
          {/* Image Gallery */}
          <div className="space-y-4">
            {/* Main Image */}
            <div className="relative w-full aspect-square bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl overflow-hidden group">
              <Image
                src={images[selectedImage] || product.image_url || "/placeholder.svg"}
                alt={product.nom}
                fill
                className="object-cover"
                priority
              />

              {product.stock < 5 && product.stock > 0 && (
                <Badge className="absolute top-4 right-4 bg-orange-500 text-white">
                  Plus que {product.stock} en stock
                </Badge>
              )}

              {product.stock === 0 && (
                <Badge className="absolute top-4 right-4 bg-red-500 text-white">
                  Rupture de stock
                </Badge>
              )}
            </div>

            {/* Thumbnail Gallery */}
            {images.length > 1 && (
              <div className="flex gap-2">
                {images.map((img, idx) => (
                  <button
                    key={idx}
                    onClick={() => setSelectedImage(idx)}
                    className={`relative w-20 h-20 rounded-lg overflow-hidden border-2 transition-all ${
                      selectedImage === idx
                        ? 'border-pink-500 scale-105'
                        : 'border-gray-200 hover:border-pink-300'
                    }`}
                  >
                    <Image
                      src={img || "/placeholder.svg"}
                      alt={`${product.nom} - ${idx + 1}`}
                      fill
                      className="object-cover"
                    />
                  </button>
                ))}
              </div>
            )}
          </div>

          {/* Product Info */}
          <div className="flex flex-col gap-6">
            <div>
              <h1 className="text-4xl font-bold mb-3">
                <span className="text-gray-900">{product.nom.split(' ')[0]}</span>
                <span className="text-pink-600 ml-2">{product.nom.split(' ').slice(1).join(' ')}</span>
              </h1>

              <div className="flex items-center gap-4 mb-4">
                <div className="flex gap-1">
                  {renderStars(Math.floor(averageRating))}
                </div>
                <span className="text-sm text-gray-600">
                  {averageRating.toFixed(1)} ({reviews.length} avis)
                </span>
              </div>

              <div className="flex items-baseline gap-4 mb-6">
                <span className="text-4xl font-bold text-pink-600">
                  {parseFloat(product.prix).toFixed(2)}€
                </span>
              </div>
            </div>

            <p className="text-gray-700 leading-relaxed text-lg">
              {product.description}
            </p>

            {/* Product Features */}
            <div className="grid grid-cols-3 gap-4 py-6 border-y border-gray-200">
              <div className="flex items-center gap-2">
                <Truck className="text-pink-500" size={20} />
                <span className="text-sm text-gray-600">Livraison gratuite</span>
              </div>
              <div className="flex items-center gap-2">
                <Shield className="text-pink-500" size={20} />
                <span className="text-sm text-gray-600">Paiement sécurisé</span>
              </div>
              <div className="flex items-center gap-2">
                <Award className="text-pink-500" size={20} />
                <span className="text-sm text-gray-600">Qualité premium</span>
              </div>
            </div>

            {/* Size Selector */}
            <div>
              <label className="block text-sm font-semibold mb-3">
                Taille : <span className="text-pink-600">{selectedSize}</span>
              </label>
              <div className="flex gap-2 flex-wrap">
                {['XS', 'S', 'M', 'L', 'XL'].map((size) => (
                  <button
                    key={size}
                    onClick={() => setSelectedSize(size)}
                    className={`px-6 py-3 border-2 rounded-lg font-semibold transition-all ${
                      selectedSize === size
                        ? 'border-pink-500 bg-pink-50 text-pink-600'
                        : 'border-gray-300 hover:border-pink-300'
                    }`}
                  >
                    {size}
                  </button>
                ))}
              </div>
            </div>

            {/* Color Selector */}
            <div>
              <label className="block text-sm font-semibold mb-3">
                Couleur : <span className="text-pink-600">{selectedColor}</span>
              </label>
              <div className="flex gap-3 flex-wrap">
                {Array.from(new Set([product.couleur, 'Noir', 'Blanc', 'Rouge', 'Rose'])).map((color, index) => (
                  <button
                    key={`${color}-${index}`}
                    onClick={() => setSelectedColor(color)}
                    className={`px-5 py-2 border-2 rounded-lg transition-all ${
                      selectedColor === color
                        ? 'border-pink-500 bg-pink-50'
                        : 'border-gray-300 hover:border-pink-300'
                    }`}
                  >
                    {color}
                  </button>
                ))}
              </div>
            </div>

            {/* Quantity Selector */}
            <div>
              <label className="block text-sm font-semibold mb-3">Quantité</label>
              <div className="flex items-center gap-4">
                <button
                  onClick={() => setQuantity(Math.max(1, quantity - 1))}
                  className="w-10 h-10 rounded-lg border-2 border-gray-300 hover:border-pink-500 transition-colors"
                >
                  -
                </button>
                <span className="text-xl font-semibold w-12 text-center">{quantity}</span>
                <button
                  onClick={() => setQuantity(Math.min(product.stock, quantity + 1))}
                  className="w-10 h-10 rounded-lg border-2 border-gray-300 hover:border-pink-500 transition-colors"
                  disabled={quantity >= product.stock}
                >
                  +
                </button>
              </div>
            </div>

            {/* Add to Cart Buttons */}
            <div className="flex gap-3 pt-4">
              <Button
                onClick={handleAddToCart}
                disabled={product.stock === 0}
                className="flex-1 h-14 text-lg font-semibold bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white rounded-xl shadow-lg transition-all"
              >
                <ShoppingCart className="mr-2" size={20} />
                {addedToCart ? 'Ajouté au panier ✓' : 'Ajouter au panier'}
              </Button>

              <Button
                onClick={handleToggleFavorite}
                variant="outline"
                className="h-14 px-6 border-2 border-gray-300 hover:border-pink-500 rounded-xl transition-all"
              >
                <Heart
                  size={24}
                  className={isFavorite ? 'fill-pink-500 text-pink-500' : 'text-gray-400'}
                />
              </Button>
            </div>

            {/* Product Details */}
            <div className="pt-6 space-y-3 text-sm border-t border-gray-200">
              <div className="flex justify-between">
                <span className="text-gray-600">Tissu</span>
                <span className="font-semibold">{product.tissu}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600">Entretien</span>
                <span className="font-semibold">Lavage délicat à la main</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600">SKU</span>
                <span className="font-semibold">FA-{product.id_produit}</span>
              </div>
            </div>
          </div>
        </div>

        {/* Tabs Section */}
        <Tabs defaultValue="reviews" className="mb-16">
          <TabsList className="grid w-full max-w-md grid-cols-2">
            <TabsTrigger value="reviews">Avis ({reviews.length})</TabsTrigger>
            <TabsTrigger value="description">Description</TabsTrigger>
          </TabsList>

          <TabsContent value="reviews" className="mt-8">
            <div className="space-y-6">
              {/* Rating Summary */}
              <div className="bg-pink-50 rounded-2xl p-8">
                <div className="flex items-center gap-8">
                  <div className="text-center">
                    <div className="text-5xl font-bold text-pink-600 mb-2">
                      {averageRating.toFixed(1)}
                    </div>
                    <div className="flex gap-1 mb-2">
                      {renderStars(Math.floor(averageRating))}
                    </div>
                    <p className="text-sm text-gray-600">{reviews.length} avis</p>
                  </div>

                  <div className="flex-1 space-y-2">
                    {[5, 4, 3, 2, 1].map((rating) => {
                      const count = reviews.filter(r => r.rating === rating).length;
                      const percentage = (count / reviews.length) * 100;
                      return (
                        <div key={rating} className="flex items-center gap-3">
                          <span className="text-sm w-12">{rating} ★</span>
                          <div className="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div
                              className="h-full bg-amber-400"
                              style={{ width: `${percentage}%` }}
                            />
                          </div>
                          <span className="text-sm text-gray-600 w-12">{count}</span>
                        </div>
                      );
                    })}
                  </div>
                </div>
              </div>

              {/* Reviews List */}
              <div className="space-y-6">
                {reviews.map((review) => (
                  <div key={review.id} className="border-b border-gray-200 pb-6 last:border-0">
                    <div className="flex items-start justify-between mb-3">
                      <div>
                        <div className="flex items-center gap-2 mb-1">
                          <span className="font-semibold">{review.author}</span>
                          {review.verified && (
                            <Badge variant="secondary" className="text-xs">
                              Achat vérifié
                            </Badge>
                          )}
                        </div>
                        <div className="flex gap-1 mb-2">
                          {renderStars(review.rating, 'sm')}
                        </div>
                      </div>
                      <span className="text-sm text-gray-500">{review.date}</span>
                    </div>
                    <p className="text-gray-700">{review.comment}</p>
                  </div>
                ))}
              </div>
            </div>
          </TabsContent>

          <TabsContent value="description" className="mt-8">
            <div className="prose max-w-none">
              <h3 className="text-2xl font-bold mb-4">À propos de ce produit</h3>
              <p className="text-gray-700 leading-relaxed mb-6">
                {product.description}
              </p>

              <h4 className="text-xl font-semibold mb-3">Caractéristiques</h4>
              <ul className="space-y-2 text-gray-700">
                <li>• Tissu : {product.tissu}</li>
                <li>• Couleur : {product.couleur}</li>
                <li>• Taille disponible : {product.taille}</li>
                <li>• Entretien : Lavage délicat à la main</li>
                <li>• Fabriqué avec amour et attention aux détails</li>
              </ul>
            </div>
          </TabsContent>
        </Tabs>

        {/* Similar Products */}
        {similarProducts.length > 0 && (
          <div className="py-12">
            <h2 className="text-3xl font-bold mb-8 text-center">
              Vous aimerez aussi
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              {similarProducts.map((prod) => (
                <Link
                  key={prod.id_produit}
                  href={`/product/${prod.id_produit}`}
                  className="group"
                >
                  <div className="relative w-full aspect-square bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl overflow-hidden mb-4">
                    <Image
                      src={prod.image_url || "/placeholder.svg"}
                      alt={prod.nom}
                      fill
                      className="object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                  </div>
                  <div className="text-center">
                    <h3 className="font-semibold mb-2 text-gray-900 group-hover:text-pink-600 transition-colors">
                      {prod.nom}
                    </h3>
                    <p className="text-xl font-bold text-pink-600">
                      {parseFloat(prod.prix).toFixed(2)}€
                    </p>
                  </div>
                </Link>
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
