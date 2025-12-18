'use client'

import Link from 'next/link'
import { Heart, ShoppingCart, User } from 'lucide-react'
import { useCart } from '@/app/context/cart-context'
import { useFavorites } from '@/app/context/favorites-context'

export default function Header() {
  const { items: cartItems } = useCart()
  const { favorites } = useFavorites()

  return (
    <header className="sticky top-0 z-50 bg-white border-b border-gray-100">
      <div className="max-w-7xl mx-auto px-4">
        <div className="flex items-center justify-between py-6">
          {/* Logo - Left side */}
          <Link href="/" className="flex-shrink-0">
            <div className="text-center">
              <div className="logo-gradient text-3xl font-bold">Aura</div>
              <p className="text-xs font-semibold tracking-widest text-foreground">FEMININE</p>
            </div>
          </Link>

          {/* Navigation Bar - Center */}
          <nav className="hidden md:flex items-center justify-center gap-8 rounded-full border border-gray-200 bg-white px-8 py-3 shadow-sm flex-1 mx-8">
            <Link
              href="/boutique"
              className="text-sm font-medium text-foreground hover:text-primary transition-colors"
            >
              Boutique
            </Link>
            <Link
              href="/univers"
              className="text-sm font-medium text-foreground hover:text-primary transition-colors"
            >
              Univers
            </Link>
            <Link
              href="/lookbook"
              className="text-sm font-medium text-foreground hover:text-primary transition-colors"
            >
              Lookbook
            </Link>
            <Link
              href="/contact"
              className="text-sm font-medium text-foreground hover:text-primary transition-colors"
            >
              Contact
            </Link>
          </nav>

          {/* Icons - Right side */}
          <div className="flex items-center gap-4">
            <Link
              href="/profile"
              className="text-primary hover:text-accent transition-colors"
              aria-label="Mon compte"
            >
              <User size={20} />
            </Link>
            <Link
              href="/favorites"
              className="text-primary hover:text-accent transition-colors relative"
              aria-label="Mes favoris"
            >
              <Heart size={20} />
              {favorites.length > 0 && (
                <span className="absolute -top-2 -right-2 h-5 w-5 rounded-full bg-accent text-white text-xs flex items-center justify-center font-bold">
                  {favorites.length}
                </span>
              )}
            </Link>
            <Link
              href="/cart"
              className="text-primary hover:text-accent transition-colors relative"
              aria-label="Panier"
            >
              <ShoppingCart size={20} />
              {cartItems.length > 0 && (
                <span className="absolute -top-2 -right-2 h-5 w-5 rounded-full bg-primary text-white text-xs flex items-center justify-center font-bold">
                  {cartItems.length}
                </span>
              )}
            </Link>
          </div>
        </div>

        {/* Mobile Navigation */}
        <div className="md:hidden flex items-center justify-center gap-6 pb-4">
          <Link
            href="/boutique"
            className="text-sm font-medium text-foreground hover:text-primary transition-colors"
          >
            Boutique
          </Link>
          <Link
            href="/univers"
            className="text-sm font-medium text-foreground hover:text-primary transition-colors"
          >
            Univers
          </Link>
          <Link
            href="/lookbook"
            className="text-sm font-medium text-foreground hover:text-primary transition-colors"
          >
            Lookbook
          </Link>
          <Link
            href="/contact"
            className="text-sm font-medium text-foreground hover:text-primary transition-colors"
          >
            Contact
          </Link>
        </div>
      </div>
    </header>
  )
}
