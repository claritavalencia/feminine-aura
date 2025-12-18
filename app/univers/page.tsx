'use client';

import Image from 'next/image';

export default function UniversPage() {
  return (
    <div className="min-h-screen bg-white">
      <div className="max-w-7xl mx-auto px-4 py-16">
        {/* Hero Section */}
        <div className="text-center mb-16">
          <h1 className="text-5xl font-bold logo-gradient mb-4">L'Univers Feminine Aura</h1>
          <p className="text-gray-600 text-lg max-w-3xl mx-auto">
            Une lingerie qui incarne l'équilibre parfait entre charisme et douceur, pour la femme moderne qui souhaite se sentir confiante et élégante.
          </p>
        </div>

        {/* About Section */}
        <div className="grid md:grid-cols-2 gap-12 mb-16 items-center">
          <div>
            <h2 className="text-3xl font-bold text-dark mb-4">Notre Histoire</h2>
            <p className="text-gray-600 mb-4 leading-relaxed">
              Feminine Aura est née d'une vision simple : créer une collection de lingerie qui célèbre la féminité sous toutes ses formes. Chaque pièce est conçue avec soin pour offrir confort, élégance et confiance.
            </p>
            <p className="text-gray-600 leading-relaxed">
              Nous croyons que la lingerie doit être bien plus qu'un simple vêtement. C'est une forme d'expression personnelle, un moyen de se sentir belle et puissante au quotidien.
            </p>
          </div>
          <div className="bg-gradient-to-br from-pink-300 to-pink-100 rounded-lg h-80 flex items-center justify-center">
            <img
              src="/brand-story.jpg"
              alt="Notre histoire"
              className="w-full h-full object-cover rounded-lg"
            />
          </div>
        </div>

        {/* Values Section */}
        <div className="mb-16">
          <h2 className="text-3xl font-bold text-dark mb-12 text-center">Nos Valeurs</h2>
          <div className="grid md:grid-cols-3 gap-8">
            {[
              {
                title: 'Qualité Premium',
                description: 'Nous sélectionnons les meilleurs tissus et matériaux pour offrir le confort et la durabilité',
                icon: '✨'
              },
              {
                title: 'Élégance Intemporelle',
                description: 'Chaque création est pensée pour transcender les tendances et rester intemporelle',
                icon: '👑'
              },
              {
                title: 'Confiance & Bien-être',
                description: 'Notre mission est de faire en sorte que chaque femme se sente confiante et belle',
                icon: '💫'
              },
            ].map((value, idx) => (
              <div key={idx} className="bg-pink-50 rounded-lg p-8 text-center">
                <div className="text-4xl mb-4">{value.icon}</div>
                <h3 className="text-xl font-bold text-dark mb-3">{value.title}</h3>
                <p className="text-gray-600">{value.description}</p>
              </div>
            ))}
          </div>
        </div>

        {/* Contact CTA */}
        <div className="bg-gradient-to-r from-pink-500 to-pink-400 rounded-lg p-12 text-center text-white">
          <h2 className="text-3xl font-bold mb-4">Des questions?</h2>
          <p className="mb-6 text-lg">Notre équipe est là pour vous aider et répondre à vos questions</p>
          <a href="/contact" className="inline-block bg-white text-primary font-semibold py-3 px-8 rounded-lg hover:bg-gray-100 transition-colors">
            Nous contacter
          </a>
        </div>
      </div>
    </div>
  );
}
