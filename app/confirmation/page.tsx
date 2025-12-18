'use client';

import { useRouter } from 'next/navigation';
import { CheckCircle } from 'lucide-react';

export default function ConfirmationPage() {
  const router = useRouter();

  return (
    <div className="min-h-screen bg-gradient-to-b from-pink-50 to-white flex items-center justify-center px-4 py-12">
      <div className="max-w-md text-center">
        <div className="mb-6">
          <div className="inline-flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
            <CheckCircle className="h-8 w-8 text-green-600" />
          </div>
        </div>

        <h1 className="text-4xl font-bold text-dark mb-4">Merci pour votre confiance</h1>
        
        <div className="bg-gradient-to-r from-dark to-accent rounded-2xl p-8 text-white mb-8">
          <p className="text-xl font-semibold mb-2">Votre commande a été bien enregistrée</p>
          <p className="text-pink-100">Un email de confirmation a été envoyé à votre adresse</p>
        </div>

        <p className="text-gray-600 mb-8">
          Découvrez d'autres collections qui pourraient vous intéresser
        </p>

        <div className="flex flex-col gap-3">
          <button
            onClick={() => router.push('/boutique')}
            className="bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition-colors"
          >
            Découvrez d'autres collections
          </button>
          <button
            onClick={() => router.push('/')}
            className="bg-gray-200 hover:bg-gray-300 text-dark font-semibold py-3 px-6 rounded-lg transition-colors"
          >
            Retourner à l'accueil
          </button>
        </div>
      </div>
    </div>
  );
}
