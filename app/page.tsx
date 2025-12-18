'use client'

import Image from 'next/image'
import Link from 'next/link'
import { Heart, Play, ChevronLeft, ChevronRight } from 'lucide-react'
import { useState } from 'react'

export default function Home() {
  const [currentTestimonial, setCurrentTestimonial] = useState(0)
  const [selectedSize, setSelectedSize] = useState('')
  const [selectedTissue, setSelectedTissue] = useState('')
  const [selectedColor, setSelectedColor] = useState('')
  const [selectedPrice, setSelectedPrice] = useState('')

  const featuredProducts = [
    {
      id: 1,
      name: 'Femina satin',
      price: 74.99,
      rating: 4.5,
      reviews: 28,
      image: '/red-lingerie-bra.jpg'
    },
    {
      id: 2,
      name: 'Sweet tissu',
      price: 74.99,
      rating: 5,
      reviews: 35,
      image: '/burgundy-lingerie-set.jpg'
    },
    {
      id: 3,
      name: 'Love brush',
      price: 70.99,
      rating: 4.5,
      reviews: 22,
      image: '/pink-elegant-lingerie.jpg'
    }
  ]

  const testimonials = [
    {
      id: 1,
      name: 'Sarah L., 28 ans',
      image: '/beautiful-woman-testimonial-1.jpg',
      review: 'J\'ai rarement porté une lingerie aussi élégante et confortable à la fois. Le satin est incroyablement doux, et les finitions en dentelle donnent une vraie touche de luxe. Je me sens belle et confiante chaque jour, merci Feminine Aura ❤️',
      rating: 5
    },
    {
      id: 2,
      name: 'Marie D., 32 ans',
      image: '/beautiful-woman-testimonial-2.jpg',
      review: 'L\'équilibre parfait entre charisme et douceur, cette création est une ode à la féminité. Chaque détail a été pensé avec soin. Je me sens belle et confiante que je la porte. Merci pour ce produit merveilleux !',
      rating: 5
    },
    {
      id: 3,
      name: 'Jessica M., 25 ans',
      image: '/beautiful-woman-testimonial-3.jpg',
      review: 'Qualité exceptionnelle, design moderne et élégant. J\'adore cette collection ! C\'est devenu mon incontournable pour me sentir bien dans ma peau. Merci pour ce produit merveilleux !',
      rating: 4.5
    }
  ]

  const nextTestimonial = () => {
    setCurrentTestimonial((prev) => (prev + 1) % testimonials.length)
  }

  const prevTestimonial = () => {
    setCurrentTestimonial((prev) => (prev - 1 + testimonials.length) % testimonials.length)
  }

  return (
    <main className="min-h-screen">
      {/* HERO SECTION */}
      <section className="relative py-20 md:py-32 px-4 md:px-8 bg-gradient-to-b from-white via-white to-pink-50">
        <div className="max-w-6xl mx-auto text-center">
          <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 leading-tight text-balance">
            <span className="block mb-2 text-gray-900 font-light tracking-tight">Sublimez votre</span>
            <span className="hero-title-gradient block text-6xl md:text-7xl lg:text-8xl font-bold">féminité avec nous.</span>
          </h1>
          
          {/* CTA Button */}
          <Link href="/boutique" className="inline-block mt-10 md:mt-12">
            <button className="btn-primary">
              Je parcours les collections
            </button>
          </Link>

          {/* Stats - Improved spacing */}
          <div className="mt-16 md:mt-20 flex flex-col md:flex-row justify-center items-center gap-8 md:gap-16 text-center">
            <div>
              <p className="text-sm md:text-base font-semibold text-gray-900">+100 clientes satisfaites</p>
            </div>
            <div className="hidden md:block w-px h-10 bg-gray-300"></div>
            <div>
              <p className="text-sm md:text-base font-semibold text-gray-900">Livraison Gratuite dès 50€</p>
            </div>
          </div>
        </div>
      </section>

      {/* FEATURED PRODUCTS SECTION */}
      <section className="py-20 md:py-32 px-4 md:px-8 bg-white">
        <div className="max-w-6xl mx-auto">
          {/* Section Title - Better hierarchy */}
          <div className="text-center mb-16">
            <p className="text-xs md:text-sm font-light text-gray-600 mb-3 tracking-widest uppercase">NOS</p>
            <h2 className="text-4xl md:text-5xl font-bold text-primary mb-3">SÉLECTIONS PHARES</h2>
            <p className="text-xs md:text-sm font-light text-gray-600 tracking-widest uppercase">POUR VOUS SUBLIMER</p>
          </div>

          {/* Search Bar - Enhanced styling */}
          <div className="flex justify-center mb-10 md:mb-12">
            <div className="relative w-full md:w-96">
              <input
                type="text"
                placeholder="Rechercher ici"
                className="w-full px-6 py-3 rounded-full border-2 border-gray-200 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all duration-200"
              />
              <button className="absolute right-2 top-1/2 -translate-y-1/2 bg-primary text-white p-2 rounded-full hover:opacity-90 active:scale-95 transition-all duration-200">
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </button>
            </div>
          </div>

          {/* Filter Options - Now with dropdowns */}
          <div className="flex flex-wrap justify-center gap-3 md:gap-4 mb-16">
            {/* Taille Dropdown */}
            <select
              value={selectedSize}
              onChange={(e) => setSelectedSize(e.target.value)}
              className="px-5 py-2 border border-gray-300 rounded-full text-gray-700 hover:border-primary focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white cursor-pointer transition-all duration-200 text-sm"
            >
              <option value="">Taille</option>
              <option value="xs">XS</option>
              <option value="s">S</option>
              <option value="m">M</option>
              <option value="l">L</option>
              <option value="xl">XL</option>
            </select>

            {/* Tissu Dropdown */}
            <select
              value={selectedTissue}
              onChange={(e) => setSelectedTissue(e.target.value)}
              className="px-5 py-2 border border-gray-300 rounded-full text-gray-700 hover:border-primary focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white cursor-pointer transition-all duration-200 text-sm"
            >
              <option value="">Tissu</option>
              <option value="satin">Satin</option>
              <option value="coton">Coton</option>
              <option value="dentelle">Dentelle</option>
              <option value="soie">Soie</option>
            </select>

            {/* Couleurs Dropdown */}
            <select
              value={selectedColor}
              onChange={(e) => setSelectedColor(e.target.value)}
              className="px-5 py-2 border border-gray-300 rounded-full text-gray-700 hover:border-primary focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white cursor-pointer transition-all duration-200 text-sm"
            >
              <option value="">Couleurs</option>
              <option value="rouge">Rouge</option>
              <option value="rose">Rose</option>
              <option value="noir">Noir</option>
              <option value="blanc">Blanc</option>
              <option value="bordeaux">Bordeaux</option>
            </select>

            {/* Prix Dropdown */}
            <select
              value={selectedPrice}
              onChange={(e) => setSelectedPrice(e.target.value)}
              className="px-5 py-2 border border-gray-300 rounded-full text-gray-700 hover:border-primary focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white cursor-pointer transition-all duration-200 text-sm"
            >
              <option value="">Prix</option>
              <option value="0-50">0€ - 50€</option>
              <option value="50-100">50€ - 100€</option>
              <option value="100-150">100€ - 150€</option>
              <option value="150+">150€+</option>
            </select>
          </div>

          {/* Products Grid - Improved layout */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10 mb-16">
            {featuredProducts.map((product) => (
              <Link key={product.id} href={`/produit/${product.id}`}>
                <div className="cursor-pointer group h-full flex flex-col">
                  {/* Product Image */}
                  <div className="relative mb-6 overflow-hidden rounded-lg bg-primary/10 aspect-square">
                    <img
                      src={product.image || "/placeholder.svg"}
                      alt={product.name}
                      className="w-full h-full object-cover card-hover"
                      loading="lazy"
                    />
                  </div>

                  {/* Product Info Card - Improved spacing */}
                  <div className="bg-secondary/5 p-6 rounded-lg flex-grow flex flex-col">
                    <div className="flex gap-1 mb-4">
                      {[...Array(5)].map((_, i) => (
                        <span key={i} className={i < Math.floor(product.rating) ? "text-yellow-400 text-lg" : "text-gray-300 text-lg"}>★</span>
                      ))}
                    </div>

                    <p className="text-primary font-bold text-2xl md:text-3xl mb-6">{product.price.toFixed(2)}€</p>

                    <div className="flex gap-2 mt-auto">
                      <button className="flex-1 btn-primary text-sm">
                        AJOUTER AU PANIER
                      </button>
                      <button className="btn-secondary px-3 py-2">
                        <Heart size={18} className="text-primary" />
                      </button>
                    </div>
                  </div>
                </div>
              </Link>
            ))}
          </div>

          {/* See All Products */}
          <div className="text-center">
            <Link href="/boutique">
              <button className="text-gray-700 hover:text-primary transition-colors font-medium text-sm md:text-base underline underline-offset-4 hover:underline-offset-2">
                Tout voir →
              </button>
            </Link>
          </div>
        </div>
      </section>

      {/* TESTIMONIALS SECTION */}
      <section className="py-20 md:py-32 px-4 md:px-8 bg-pink-50">
        <div className="max-w-6xl mx-auto">
          {/* Section Title - Improved typography */}
          <h2 className="text-center text-4xl md:text-5xl lg:text-6xl font-bold text-primary mb-6">
            ELLES PARTAGENT LEUR EXPÉRIENCE
          </h2>
          <p className="text-center text-gray-700 mb-16 text-base md:text-lg lg:text-xl font-light leading-relaxed">
            Découvrez ce que nos clientes pensent de Feminine Aura.
          </p>

          <div className="flex items-center justify-center gap-4 md:gap-8">
            {/* Left Arrow */}
            <button 
              onClick={prevTestimonial}
              className="flex-shrink-0 text-primary hover:opacity-70 active:scale-90 transition-all duration-200 p-2 md:p-3"
              aria-label="Previous testimonial"
            >
              <ChevronLeft size={32} />
            </button>

            {/* Testimonials Display - Show all 3 on desktop, 1 on mobile */}
            <div className="flex-1">
              <div className="hidden md:grid grid-cols-3 gap-8">
                {testimonials.map((testimonial) => (
                  <div key={testimonial.id} className="group">
                    {/* Image Container */}
                    <div className="relative overflow-hidden rounded-3xl aspect-[3/4] bg-gray-300 card-hover mb-5">
                      <img
                        src={testimonial.image || "/placeholder.svg"}
                        alt={testimonial.name}
                        className="w-full h-full object-cover"
                        loading="lazy"
                      />
                      <div className="absolute inset-0 flex items-center justify-center bg-black/10 group-hover:bg-black/30 transition-all duration-300">
                        <button className="bg-primary text-white rounded-full p-4 group-hover:scale-110 transition-transform duration-300" aria-label={`Watch ${testimonial.name} video`}>
                          <Play size={24} fill="currentColor" />
                        </button>
                      </div>
                    </div>

                    {/* Review Content */}
                    <div className="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                      {/* Stars */}
                      <div className="flex gap-1 mb-3">
                        {[...Array(5)].map((_, i) => (
                          <span key={i} className={i < Math.floor(testimonial.rating) ? "text-yellow-400 text-base" : "text-gray-300 text-base"}>★</span>
                        ))}
                      </div>

                      {/* Review Text */}
                      <p className="text-gray-700 text-sm leading-relaxed mb-4 line-clamp-3 italic">
                        "{testimonial.review}"
                      </p>

                      {/* Name */}
                      <p className="font-semibold text-gray-900 text-sm">{testimonial.name}</p>
                    </div>
                  </div>
                ))}
              </div>

              {/* Mobile - Single testimonial carousel */}
              <div className="md:hidden">
                <div className="group">
                  <div className="relative overflow-hidden rounded-3xl aspect-[3/4] bg-gray-300 max-w-xs mx-auto card-hover mb-5">
                    <img
                      src={testimonials[currentTestimonial].image || "/placeholder.svg"}
                      alt={testimonials[currentTestimonial].name}
                      className="w-full h-full object-cover"
                      loading="lazy"
                    />
                    <div className="absolute inset-0 flex items-center justify-center bg-black/10 group-hover:bg-black/30 transition-all duration-300">
                      <button className="bg-primary text-white rounded-full p-4 group-hover:scale-110 transition-transform duration-300" aria-label={`Watch ${testimonials[currentTestimonial].name} video`}>
                        <Play size={24} fill="currentColor" />
                      </button>
                    </div>
                  </div>

                  {/* Review Content */}
                  <div className="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                    {/* Stars */}
                    <div className="flex gap-1 mb-3">
                      {[...Array(5)].map((_, i) => (
                        <span key={i} className={i < Math.floor(testimonials[currentTestimonial].rating) ? "text-yellow-400 text-base" : "text-gray-300 text-base"}>★</span>
                      ))}
                    </div>

                    {/* Review Text */}
                    <p className="text-gray-700 text-sm leading-relaxed mb-4 italic">
                      "{testimonials[currentTestimonial].review}"
                    </p>

                    {/* Name */}
                    <p className="font-semibold text-gray-900 text-sm">{testimonials[currentTestimonial].name}</p>
                  </div>
                </div>
              </div>
            </div>

            {/* Right Arrow */}
            <button 
              onClick={nextTestimonial}
              className="flex-shrink-0 text-primary hover:opacity-70 active:scale-90 transition-all duration-200 p-2 md:p-3"
              aria-label="Next testimonial"
            >
              <ChevronRight size={32} />
            </button>
          </div>
        </div>
      </section>

      {/* NEWSLETTER SECTION */}
      <section className="py-20 md:py-32 px-4 md:px-8 bg-gradient-to-r from-primary via-primary to-accent">
        <div className="max-w-2xl mx-auto text-center">
          <div className="bg-white/10 backdrop-blur-sm border-2 border-white/20 rounded-3xl p-8 md:p-12 md:pb-14 mb-10 md:mb-12">
            <h2 className="text-white text-3xl md:text-4xl lg:text-5xl font-bold mb-4 leading-tight">
              REJOIGNEZ L'UNIVERS FEMININE AURA
            </h2>
            <p className="text-white/90 text-sm md:text-base font-light">
              Recevez nos nouveautés et offres exclusives, sans spam.
            </p>
          </div>

          <div className="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <input
              type="email"
              placeholder="Votre email"
              className="flex-1 px-6 py-4 rounded-full focus:outline-none text-gray-900 placeholder-gray-700 text-base font-semibold shadow-lg focus:ring-4 focus:ring-white/40 transition-all duration-200 bg-white"
              aria-label="Email for newsletter"
            />
            <button className="bg-white text-primary px-8 py-4 rounded-full font-bold hover:bg-gray-100 active:scale-95 transition-all duration-200 whitespace-nowrap shadow-lg text-base">
              S'inscrire
            </button>
          </div>
        </div>
      </section>
    </main>
  )
}
