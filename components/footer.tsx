'use client'

import Link from 'next/link'
import { Mail } from 'lucide-react'

export default function Footer() {
  const currentYear = new Date().getFullYear()

  return (
    <footer className="bg-secondary text-white">
      <div className="max-w-7xl mx-auto px-4 py-12 md:py-16">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
          {/* Brand */}
          <div className="md:col-span-1">
            <div className="flex items-center gap-1 mb-4">
              <span className="text-2xl font-bold text-primary">Aura</span>
            </div>
            <p className="text-xs tracking-widest mb-4 text-white/80">FEMININE</p>
            <p className="text-sm text-white/70 leading-relaxed">
              Une lingerie qui incarne l'équilibre parfait entre charisme et douceur.
            </p>
          </div>

          {/* Boutique */}
          <div>
            <h3 className="font-semibold text-white mb-4">Boutique</h3>
            <ul className="space-y-2 text-sm text-white/80">
              <li>
                <Link href="/boutique" className="hover:text-primary transition-colors">
                  Tous les produits
                </Link>
              </li>
              <li>
                <Link href="/boutique?filter=new" className="hover:text-primary transition-colors">
                  Nouveautés
                </Link>
              </li>
              <li>
                <Link href="/boutique?filter=bestsellers" className="hover:text-primary transition-colors">
                  Best-sellers
                </Link>
              </li>
            </ul>
          </div>

          {/* À propos */}
          <div>
            <h3 className="font-semibold text-white mb-4">À propos</h3>
            <ul className="space-y-2 text-sm text-white/80">
              <li>
                <Link href="/about" className="hover:text-primary transition-colors">
                  À propos de nous
                </Link>
              </li>
              <li>
                <Link href="/contact" className="hover:text-primary transition-colors">
                  Contact
                </Link>
              </li>
              <li>
                <Link href="/faq" className="hover:text-primary transition-colors">
                  FAQ
                </Link>
              </li>
            </ul>
          </div>

          {/* Newsletter */}
          <div>
            <h3 className="font-semibold text-white mb-4">Newsletter</h3>
            <p className="text-sm text-white/80 mb-3">
              Recevez nos exclusivités et offres spéciales
            </p>
            <div className="flex">
              <input
                type="email"
                placeholder="Votre email"
                className="px-3 py-2 rounded-l text-foreground text-sm flex-1 focus:outline-none"
              />
              <button className="bg-primary text-white px-3 py-2 rounded-r hover:bg-primary/90 transition-colors">
                <Mail size={18} />
              </button>
            </div>
          </div>
        </div>

        {/* Divider */}
        <div className="h-px bg-white/20 mb-8"></div>

        {/* Bottom */}
        <div className="flex flex-col md:flex-row justify-between items-center text-xs text-white/70 gap-4">
          <p>&copy; {currentYear} Feminine Aura - Tous droits réservés</p>
          <div className="flex gap-4">
            <Link href="/legal/conditions" className="hover:text-primary transition-colors">
              Conditions d'utilisation
            </Link>
            <Link href="/legal/privacy" className="hover:text-primary transition-colors">
              Politique de confidentialité
            </Link>
            <Link href="/legal/cgv" className="hover:text-primary transition-colors">
              CGV
            </Link>
          </div>
        </div>
      </div>
    </footer>
  )
}
