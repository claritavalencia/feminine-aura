'use client'

import { useCart } from '@/app/context/cart-context'
import Link from 'next/link'
import { Heart, Trash2, Plus, Minus, ShoppingCart } from 'lucide-react'
import { useState, useEffect } from 'react'

export default function CartPage() {
  const { items, removeItem, updateQuantity, total } = useCart()
  const [isClient, setIsClient] = useState(false)

  useEffect(() => {
    setIsClient(true)
  }, [])

  if (!isClient) return null

  const shipping = 0
  const finalTotal = total + shipping

  return (
    <div className="min-h-screen bg-white py-12">
      <div className="max-w-6xl mx-auto px-4">
        {/* Breadcrumb */}
        <div className="text-sm text-gray-600 mb-8">
          <Link href="/" className="hover:text-primary transition-colors">Accueil</Link>
          <span className="mx-2">&gt;</span>
          <span className="text-primary font-semibold">Panier</span>
          <span className="mx-2">&gt;</span>
          <span className="text-gray-400">Paiement</span>
          <span className="mx-2">&gt;</span>
          <span className="text-gray-400">Confirmation</span>
        </div>

        {items.length === 0 ? (
          <div className="text-center py-20">
            <ShoppingCart className="w-16 h-16 mx-auto mb-4 text-gray-300" />
            <h2 className="text-2xl font-bold text-gray-600 mb-4">Votre panier est vide</h2>
            <p className="text-gray-500 mb-8">Découvrez notre collection de lingerie premium</p>
            <Link href="/boutique" className="btn-primary">
              Continuer vos achats
            </Link>
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Cart Items */}
            <div className="lg:col-span-2">
              <div className="flex items-center justify-between mb-8">
                <h1 className="text-3xl font-bold text-foreground">Votre Panier</h1>
                <Link 
                  href="/checkout"
                  className="btn-primary text-sm"
                >
                  Passez au paiement
                </Link>
              </div>

              <div className="space-y-6">
                {items.map((item) => (
                  <div 
                    key={item.id}
                    className="flex gap-4 pb-6 border-b border-gray-200 hover:bg-gray-50/50 p-4 rounded-lg transition-colors"
                  >
                    {/* Product Image */}
                    <div className="w-24 h-32 flex-shrink-0">
                      <img
                        src={item.image || "/placeholder.svg"}
                        alt={item.name}
                        className="w-full h-full object-cover rounded"
                      />
                    </div>

                    {/* Product Info */}
                    <div className="flex-1">
                      <h3 className="text-lg font-semibold text-foreground mb-1">
                        {item.name}
                      </h3>
                      <p className="text-gray-600 text-sm mb-4">
                        {item.color && <span>{item.color}</span>}
                        {item.size && <span className="ml-3">{item.size}</span>}
                      </p>

                      <div className="flex items-center justify-between">
                        <div className="flex items-center gap-3 bg-gray-100 rounded-full px-4 py-2">
                          <button
                            onClick={() => updateQuantity(item.id, item.quantity - 1)}
                            className="text-primary hover:opacity-70 transition-opacity"
                            aria-label="Diminuer la quantité"
                          >
                            <Minus size={16} />
                          </button>
                          <span className="w-8 text-center font-semibold">{item.quantity}</span>
                          <button
                            onClick={() => updateQuantity(item.id, item.quantity + 1)}
                            className="text-primary hover:opacity-70 transition-opacity"
                            aria-label="Augmenter la quantité"
                          >
                            <Plus size={16} />
                          </button>
                        </div>

                        {/* Actions */}
                        <div className="flex items-center gap-4">
                          <p className="text-lg font-bold text-primary">
                            {(item.price * item.quantity).toFixed(2)}€
                          </p>
                          <button
                            onClick={() => removeItem(item.id)}
                            className="text-gray-400 hover:text-red-500 transition-colors"
                            aria-label="Supprimer du panier"
                          >
                            <Trash2 size={20} />
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>

              {/* Similar Products */}
              <div className="mt-12">
                <h2 className="text-2xl font-bold text-foreground mb-6">
                  Des Produits similaires qui pourraient vous intéresser
                </h2>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                  {[1, 2, 3].map((i) => (
                    <div key={i} className="group cursor-pointer">
                      <div className="relative bg-primary/20 rounded-lg overflow-hidden mb-4 aspect-square">
                        <img
                          src={`/lingerie-product-.jpg?height=300&width=300&query=lingerie-product-${i}`}
                          alt={`Similar product ${i}`}
                          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        />
                      </div>
                      <div className="text-center">
                        <p className="text-yellow-400 text-sm mb-1">★★★★★ 75.00€</p>
                        <button className="text-primary font-semibold text-sm hover:opacity-70 transition-opacity">
                          Ajouter au panier
                        </button>
                        <button className="text-gray-400 hover:text-red-500 transition-colors ml-2" aria-label="Ajouter aux favoris">
                          <Heart size={16} />
                        </button>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Summary */}
            <div className="lg:col-span-1">
              <div className="bg-gray-50 rounded-lg p-6 sticky top-24">
                <h3 className="text-lg font-bold text-foreground mb-6">Résumé</h3>

                <div className="space-y-4 mb-6">
                  <div className="flex justify-between text-gray-600">
                    <span>Sous-total</span>
                    <span>{total.toFixed(2)}€</span>
                  </div>
                  <div className="flex justify-between text-gray-600">
                    <span>Livraison</span>
                    <span className="text-green-600 font-semibold">Gratuite</span>
                  </div>
                  <div className="border-t border-gray-200 pt-4 flex justify-between text-xl font-bold text-foreground">
                    <span>Total</span>
                    <span className="text-primary">{finalTotal.toFixed(2)}€</span>
                  </div>
                </div>

                <Link 
                  href="/checkout"
                  className="btn-primary w-full text-center block mb-3"
                >
                  Continuer vers le paiement
                </Link>

                <Link 
                  href="/boutique"
                  className="w-full text-center text-primary font-semibold py-3 rounded-full border-2 border-primary hover:bg-primary/5 transition-colors block"
                >
                  Continuer vos achats
                </Link>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  )
}
