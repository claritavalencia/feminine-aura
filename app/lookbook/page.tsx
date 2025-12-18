'use client';

import Image from 'next/image';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useState } from 'react';

const lookbookImages = [
  { id: 1, src: '/lingerie-lookbook-1.jpg', alt: 'Lookbook 1' },
  { id: 2, src: '/lingerie-lookbook-2.jpg', alt: 'Lookbook 2' },
  { id: 3, src: '/lingerie-lookbook-3.jpg', alt: 'Lookbook 3' },
  { id: 4, src: '/lingerie-lookbook-4.jpg', alt: 'Lookbook 4' },
  { id: 5, src: '/lingerie-lookbook-5.jpg', alt: 'Lookbook 5' },
  { id: 6, src: '/lingerie-lookbook-6.jpg', alt: 'Lookbook 6' },
];

export default function LookbookPage() {
  const [currentIndex, setCurrentIndex] = useState(0);

  const next = () => {
    setCurrentIndex((prev) => (prev + 3 >= lookbookImages.length ? 0 : prev + 3));
  };

  const prev = () => {
    setCurrentIndex((prev) => (prev - 3 < 0 ? Math.max(0, lookbookImages.length - 3) : prev - 3));
  };

  return (
    <div className="min-h-screen bg-white">
      <div className="max-w-7xl mx-auto px-4 py-16">
        <div className="text-center mb-16">
          <h1 className="text-5xl font-bold logo-gradient mb-4">Lookbook</h1>
          <p className="text-gray-600 text-lg max-w-2xl mx-auto">
            Découvrez nos sélections de la saison et trouvez l'inspiration pour sublimer votre féminité
          </p>
        </div>

        <div className="relative">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {lookbookImages.slice(currentIndex, currentIndex + 3).map((image) => (
              <div key={image.id} className="aspect-square overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                <img
                  src={image.src || "/placeholder.svg"}
                  alt={image.alt}
                  className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                />
              </div>
            ))}
          </div>

          <div className="flex items-center justify-between mt-8">
            <button
              onClick={prev}
              className="p-3 rounded-full bg-primary text-white hover:bg-primary-dark transition-colors"
              aria-label="Précédent"
            >
              <ChevronLeft size={24} />
            </button>

            <div className="flex gap-3 justify-center">
              {Array.from({ length: Math.ceil(lookbookImages.length / 3) }).map((_, idx) => (
                <button
                  key={idx}
                  onClick={() => setCurrentIndex(idx * 3)}
                  className={`h-2 rounded-full transition-all ${
                    idx === Math.floor(currentIndex / 3)
                      ? 'bg-primary w-6'
                      : 'bg-gray-300 w-2'
                  }`}
                  aria-label={`Aller à la page ${idx + 1}`}
                />
              ))}
            </div>

            <button
              onClick={next}
              className="p-3 rounded-full bg-primary text-white hover:bg-primary-dark transition-colors"
              aria-label="Suivant"
            >
              <ChevronRight size={24} />
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
