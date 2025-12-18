'use client'

import { useState, useMemo, useEffect } from 'react'
import { Heart, Star } from 'lucide-react'
import { Button } from '@/components/ui/button'
import ProductCard from '@/components/product-card'
import { getProducts, Product } from '@/lib/api'

// Mock product data - BACKUP si l'API ne répond pas
const mockProducts = [
  {
    id: 1,
    name: 'Femina satin',
    price: 74.99,
    rating: 4.5,
    reviews: 120,
    image: '/red-lingerie-bra.jpg',
    sizes: ['XS', 'S', 'M', 'L'],
    colors: ['Rouge', 'Noir', 'Blanc'],
    material: 'Satin',
  },
  {
    id: 2,
    name: 'Sweet tissu',
    price: 74.99,
    rating: 4.7,
    reviews: 95,
    image: '/burgundy-lingerie-set.jpg',
    sizes: ['XS', 'S', 'M', 'L', 'XL'],
    colors: ['Bordeaux', 'Noir'],
    material: 'Dentelle',
  },
  {
    id: 3,
    name: 'Love brush',
    price: 79.57,
    rating: 4.6,
    reviews: 87,
    image: '/pink-lingerie-top.jpg',
    sizes: ['S', 'M', 'L'],
    colors: ['Rose', 'Noir'],
    material: 'Coton',
  },
  {
    id: 4,
    name: 'Love mood',
    price: 74.99,
    rating: 4.5,
    reviews: 110,
    image: '/elegant-lingerie-bra.jpg',
    sizes: ['XS', 'S', 'M', 'L', 'XL'],
    colors: ['Noir', 'Blanc'],
    material: 'Satin',
  },
  {
    id: 5,
    name: 'Love Kit',
    price: 75.00,
    rating: 4.8,
    reviews: 200,
    image: '/luxury-lingerie-set.jpg',
    sizes: ['M', 'L'],
    colors: ['Bordeaux', 'Rose'],
    material: 'Satin',
  },
  {
    id: 6,
    name: 'Elegance',
    price: 72.20,
    rating: 4.4,
    reviews: 65,
    image: '/black-lingerie-elegant.jpg',
    sizes: ['XS', 'S', 'M'],
    colors: ['Noir'],
    material: 'Dentelle',
  },
  {
    id: 7,
    name: 'Premium collection',
    price: 73.00,
    rating: 4.6,
    reviews: 140,
    image: '/premium-lingerie-collection.jpg',
    sizes: ['S', 'M', 'L', 'XL'],
    colors: ['Bordeaux', 'Rose', 'Noir'],
    material: 'Satin',
  },
  {
    id: 8,
    name: 'Classique',
    price: 75.00,
    rating: 4.7,
    reviews: 175,
    image: '/classic-lingerie-design.jpg',
    sizes: ['XS', 'S', 'M', 'L'],
    colors: ['Blanc', 'Noir'],
    material: 'Coton',
  },
]

export default function BoutiquePage() {
  const [searchQuery, setSearchQuery] = useState('')
  const [selectedSizes, setSelectedSizes] = useState<string[]>([])
  const [selectedMaterials, setSelectedMaterials] = useState<string[]>([])
  const [selectedColors, setSelectedColors] = useState<string[]>([])
  const [priceRange, setPriceRange] = useState([5, 200])
  const [sortBy, setSortBy] = useState('all')
  const [products, setProducts] = useState<any[]>([])
  const [loading, setLoading] = useState(true)

  // Charger les produits depuis l'API
  useEffect(() => {
    async function loadProducts() {
      try {
        setLoading(true)
        const response = await getProducts()

        if (response.success && response.data?.produits) {
          // Transformer les produits de l'API au format du frontend
          const transformedProducts = response.data.produits.map((p: Product) => ({
            id: p.id_produit,
            name: p.nom,
            price: parseFloat(p.prix),
            rating: 4.5, // Valeur par défaut - à calculer depuis les avis
            reviews: 100, // Valeur par défaut
            image: p.image_url,
            sizes: [p.taille], // Un seul taille par produit dans la DB
            colors: [p.couleur], // Une seule couleur par produit dans la DB
            material: p.tissu,
          }))

          setProducts(transformedProducts)
        } else {
          console.warn('API failed, using mock data')
          setProducts(mockProducts)
        }
      } catch (error) {
        console.error('Error loading products:', error)
        setProducts(mockProducts)
      } finally {
        setLoading(false)
      }
    }

    loadProducts()
  }, [])

  // Extract unique values from products
  const sizes = Array.from(new Set(products.flatMap(p => p.sizes)))
  const materials = Array.from(new Set(products.flatMap(p => p.material)))
  const colors = Array.from(new Set(products.flatMap(p => p.colors)))

  // Filter products
  const filteredProducts = useMemo(() => {
    return products.filter(product => {
      const matchesSearch = product.name.toLowerCase().includes(searchQuery.toLowerCase())
      const matchesSizes = selectedSizes.length === 0 || selectedSizes.some(s => product.sizes.includes(s))
      const matchesMaterials = selectedMaterials.length === 0 || selectedMaterials.includes(product.material)
      const matchesColors = selectedColors.length === 0 || selectedColors.some(c => product.colors.includes(c))
      const matchesPrice = product.price >= priceRange[0] && product.price <= priceRange[1]

      return matchesSearch && matchesSizes && matchesMaterials && matchesColors && matchesPrice
    }).sort((a, b) => {
      if (sortBy === 'price-low') return a.price - b.price
      if (sortBy === 'price-high') return b.price - a.price
      if (sortBy === 'rating') return b.rating - a.rating
      if (sortBy === 'newest') return b.id - a.id
      return 0
    })
  }, [products, searchQuery, selectedSizes, selectedMaterials, selectedColors, priceRange, sortBy])

  const toggleFilter = (value: string, setter: Function, state: string[]) => {
    setter(state.includes(value) ? state.filter(s => s !== value) : [...state, value])
  }

  return (
    <div className="min-h-screen bg-background">
      {/* Header */}
      <div className="bg-gradient-to-r from-[#F34792] to-[#FF9FC9] py-8 text-center">
        <h1 className="text-4xl font-bold text-white mb-3">LA COLLECTION FEMININE AURA</h1>
        <p className="text-white text-lg">Toute notre gamme, conçue pour l'élégance au quotidien.</p>
      </div>

      {/* Search Bar */}
      <div className="max-w-6xl mx-auto px-4 py-8">
        <div className="relative mb-8">
          <input
            type="text"
            placeholder="Rechercher ici"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full px-6 py-3 bg-gradient-to-r from-[#F34792] to-[#FF9FC9] rounded-full text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#CB0059]"
          />
          <button className="absolute right-3 top-1/2 -translate-y-1/2 text-white hover:scale-110 transition">
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </button>
        </div>

        {/* Category Tabs */}
        <div className="flex gap-3 mb-8 flex-wrap">
          <button className={`px-4 py-2 rounded-lg font-medium transition ${sortBy === 'all' ? 'bg-white border-2 border-[#F34792] text-[#F34792]' : 'bg-white text-foreground border border-gray-300'}`}
            onClick={() => setSortBy('all')}>
            Tous les produits
          </button>
          <button className={`px-4 py-2 rounded-lg font-medium transition ${sortBy === 'newest' ? 'bg-gradient-to-r from-[#F34792] to-[#FF9FC9] text-white' : 'bg-white text-foreground border border-gray-300'}`}
            onClick={() => setSortBy('newest')}>
            Nouveautés
          </button>
          <button className={`px-4 py-2 rounded-lg font-medium transition ${sortBy === 'rating' ? 'bg-gradient-to-r from-[#F34792] to-[#FF9FC9] text-white' : 'bg-white text-foreground border border-gray-300'}`}
            onClick={() => setSortBy('rating')}>
            Best-sellers
          </button>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
          {/* Filters Sidebar */}
          <div className="lg:col-span-1">
            <div className="space-y-6 sticky top-4">
              {/* Size Filter */}
              <div>
                <h3 className="font-bold text-foreground mb-3">Taille</h3>
                <div className="space-y-2">
                  {sizes.map(size => (
                    <label key={size} className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="checkbox"
                        checked={selectedSizes.includes(size)}
                        onChange={() => toggleFilter(size, setSelectedSizes, selectedSizes)}
                        className="w-4 h-4 accent-[#F34792]"
                      />
                      <span className="text-sm">{size}</span>
                    </label>
                  ))}
                </div>
              </div>

              {/* Material Filter */}
              <div>
                <h3 className="font-bold text-foreground mb-3">Tissu</h3>
                <div className="space-y-2">
                  {materials.map(material => (
                    <label key={material} className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="checkbox"
                        checked={selectedMaterials.includes(material)}
                        onChange={() => toggleFilter(material, setSelectedMaterials, selectedMaterials)}
                        className="w-4 h-4 accent-[#F34792]"
                      />
                      <span className="text-sm">{material}</span>
                    </label>
                  ))}
                </div>
              </div>

              {/* Color Filter */}
              <div>
                <h3 className="font-bold text-foreground mb-3">Couleurs</h3>
                <div className="space-y-2">
                  {colors.map(color => (
                    <label key={color} className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="checkbox"
                        checked={selectedColors.includes(color)}
                        onChange={() => toggleFilter(color, setSelectedColors, selectedColors)}
                        className="w-4 h-4 accent-[#F34792]"
                      />
                      <span className="text-sm">{color}</span>
                    </label>
                  ))}
                </div>
              </div>

              {/* Price Filter */}
              <div>
                <h3 className="font-bold text-foreground mb-3">Prix</h3>
                <div className="flex gap-2 items-center">
                  <input
                    type="number"
                    min="5"
                    max="200"
                    value={priceRange[0]}
                    onChange={(e) => setPriceRange([Math.max(5, Math.min(parseInt(e.target.value) || 5, priceRange[1])), priceRange[1]])}
                    className="w-16 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#F34792]"
                  />
                  <span className="text-gray-400">-</span>
                  <input
                    type="number"
                    min="5"
                    max="200"
                    value={priceRange[1]}
                    onChange={(e) => setPriceRange([priceRange[0], Math.min(200, Math.max(parseInt(e.target.value) || 200, priceRange[0]))])}
                    className="w-16 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#F34792]"
                  />
                  <span className="text-sm text-gray-500">€</span>
                </div>
              </div>
            </div>
          </div>

          {/* Products Grid */}
          <div className="lg:col-span-3">
            {loading ? (
              <div className="text-center py-12">
                <div className="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-[#F34792] border-r-transparent"></div>
                <p className="mt-4 text-lg text-muted-foreground">Chargement des produits...</p>
              </div>
            ) : filteredProducts.length > 0 ? (
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {filteredProducts.map(product => (
                  <ProductCard key={product.id} product={product} />
                ))}
              </div>
            ) : (
              <div className="text-center py-12">
                <p className="text-lg text-muted-foreground">Aucun produit ne correspond à vos critères.</p>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  )
}
