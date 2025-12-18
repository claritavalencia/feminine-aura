'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import { Heart } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { useCart } from '@/app/context/cart-context'
import { useFavorites } from '@/app/context/favorites-context'

interface Product {
  id: number
  name: string
  price: number
  rating: number
  reviews: number
  image: string
}

export default function ProductCard({ product }: { product: Product }) {
  const { addItem } = useCart()
  const { addFavorite, removeFavorite, isFavorite: checkIsFavorite } = useFavorites()
  const [isFavorite, setIsFavorite] = useState(false)

  // Vérifier si le produit est dans les favoris
  useEffect(() => {
    setIsFavorite(checkIsFavorite(product.id.toString()))
  }, [product.id, checkIsFavorite])

  return (
    <Link href={`/product/${product.id}`}>
      <div className="group cursor-pointer bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all">
        {/* Image */}
        <div className="relative overflow-hidden bg-gradient-to-br from-[#F34792]/20 to-[#FF9FC9]/20 h-64">
          <img
            src={product.image || "/placeholder.svg"}
            alt={product.name}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
          />
        </div>

        {/* Info */}
        <div className="p-4 bg-gradient-to-t from-[#1A0A1A] to-[#2D1A2D] text-white">
          <div className="flex justify-between items-start mb-2">
            <div>
              <div className="flex items-center gap-1 mb-1">
                <span className="text-sm">{'★'.repeat(Math.floor(product.rating))}</span>
                {product.rating % 1 !== 0 && <span className="text-sm">☆</span>}
              </div>
              <p className="text-xs text-gray-300">#CTAGS</p>
            </div>
            <button
              onClick={async (e) => {
                e.preventDefault()
                e.stopPropagation()
                try {
                  if (isFavorite) {
                    await removeFavorite(product.id.toString())
                  } else {
                    await addFavorite({
                      id: product.id.toString(),
                      name: product.name,
                      price: product.price,
                      image: product.image,
                      rating: product.rating,
                    })
                  }
                  setIsFavorite(!isFavorite)
                } catch (error) {
                  console.error('Error toggling favorite:', error)
                }
              }}
              className="transition-colors"
            >
              <Heart
                size={20}
                fill={isFavorite ? '#FF9FC9' : 'none'}
                stroke={isFavorite ? '#FF9FC9' : 'white'}
              />
            </button>
          </div>

          <p className="font-bold text-lg mb-3">{product.price.toFixed(2)}€</p>

          <Button
            onClick={async (e) => {
              e.preventDefault()
              e.stopPropagation()
              try {
                await addItem({
                  id: product.id.toString(),
                  name: product.name,
                  price: product.price,
                  quantity: 1,
                  image: product.image,
                })
              } catch (error) {
                console.error('Error adding to cart:', error)
              }
            }}
            className="w-full bg-gradient-to-r from-[#F34792] to-[#FF9FC9] text-white font-bold hover:opacity-90 transition"
          >
            AJOUTER AU PANIER
          </Button>
        </div>
      </div>
    </Link>
  )
}
