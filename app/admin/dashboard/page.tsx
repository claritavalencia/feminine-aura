'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import {
  LayoutDashboard,
  Package,
  ShoppingCart,
  Users,
  TrendingUp,
  DollarSign,
  Box,
  LogOut,
  Plus,
  Edit,
  Trash2,
  Search,
  Filter
} from 'lucide-react';
import { useAdmin } from '@/app/context/admin-context';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
  DialogFooter,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

export default function AdminDashboard() {
  const router = useRouter();
  const { admin, isAdmin, logoutAdmin, loading } = useAdmin();

  const [products, setProducts] = useState<any[]>([]);
  const [categories, setCategories] = useState<any[]>([]);
  const [orders, setOrders] = useState<any[]>([]);
  const [stats, setStats] = useState({
    totalRevenue: 0,
    totalOrders: 0,
    totalProducts: 0,
    newCustomers: 0,
  });

  const [isProductDialogOpen, setIsProductDialogOpen] = useState(false);
  const [editingProduct, setEditingProduct] = useState<any>(null);
  const [productForm, setProductForm] = useState({
    nom: '',
    description: '',
    prix: '',
    stock: '',
    id_categorie: '',
    couleur: '',
    taille: '',
    tissu: '',
    image_url: '',
  });

  useEffect(() => {
    if (!loading && !isAdmin) {
      router.push('/admin');
    }
  }, [isAdmin, loading, router]);

  useEffect(() => {
    if (isAdmin) {
      loadData();
    }
  }, [isAdmin]);

  const loadData = async () => {
    try {
      // Charger les produits
      const productsRes = await fetch(
        `${process.env.NEXT_PUBLIC_API_URL}/produits`
      );
      const productsData = await productsRes.json();
      if (productsData.success) {
        setProducts(productsData.data.produits || []);
        setStats(prev => ({
          ...prev,
          totalProducts: productsData.data.produits?.length || 0
        }));
      }

      // Charger les catégories
      const categoriesRes = await fetch(
        `${process.env.NEXT_PUBLIC_API_URL}/categories`
      );
      const categoriesData = await categoriesRes.json();
      if (categoriesData.success) {
        setCategories(categoriesData.data.categories || []);
      }

      // Mock data pour les commandes (en attendant l'implémentation)
      setOrders([
        { id: 1, numero: 'CMD-001', client: 'Sophie M.', total: 89.99, statut: 'confirmee', date: '2024-12-01' },
        { id: 2, numero: 'CMD-002', client: 'Marie L.', total: 129.99, statut: 'en_preparation', date: '2024-12-02' },
      ]);

      setStats(prev => ({
        ...prev,
        totalRevenue: 1250.50,
        totalOrders: 15,
        newCustomers: 8,
      }));

    } catch (error) {
      console.error('Error loading data:', error);
    }
  };

  const handleLogout = () => {
    logoutAdmin();
  };

  const handleProductSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    try {
      const url = editingProduct
        ? `${process.env.NEXT_PUBLIC_API_URL}/produits/${editingProduct.id_produit}`
        : `${process.env.NEXT_PUBLIC_API_URL}/produits`;

      const method = editingProduct ? 'PUT' : 'POST';

      const response = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${admin?.token}`,
        },
        body: JSON.stringify(productForm),
      });

      const data = await response.json();

      if (data.success) {
        setIsProductDialogOpen(false);
        resetProductForm();
        loadData();
        alert(editingProduct ? 'Produit modifié avec succès' : 'Produit créé avec succès');
      } else {
        alert('Erreur : ' + data.message);
      }
    } catch (error) {
      console.error('Error saving product:', error);
      alert('Erreur lors de la sauvegarde du produit');
    }
  };

  const handleDeleteProduct = async (productId: number) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
      return;
    }

    try {
      const response = await fetch(
        `${process.env.NEXT_PUBLIC_API_URL}/produits/${productId}`,
        {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${admin?.token}`,
          },
        }
      );

      const data = await response.json();

      if (data.success) {
        loadData();
        alert('Produit supprimé avec succès');
      } else {
        alert('Erreur : ' + data.message);
      }
    } catch (error) {
      console.error('Error deleting product:', error);
      alert('Erreur lors de la suppression');
    }
  };

  const openEditDialog = (product: any) => {
    setEditingProduct(product);
    setProductForm({
      nom: product.nom,
      description: product.description,
      prix: product.prix,
      stock: product.stock.toString(),
      id_categorie: product.id_categorie.toString(),
      couleur: product.couleur,
      taille: product.taille,
      tissu: product.tissu,
      image_url: product.image_url,
    });
    setIsProductDialogOpen(true);
  };

  const resetProductForm = () => {
    setEditingProduct(null);
    setProductForm({
      nom: '',
      description: '',
      prix: '',
      stock: '',
      id_categorie: '',
      couleur: '',
      taille: '',
      tissu: '',
      image_url: '',
    });
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-50">
        <div className="text-center">
          <div className="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-pink-500 border-r-transparent"></div>
          <p className="mt-4 text-lg text-gray-600">Chargement...</p>
        </div>
      </div>
    );
  }

  if (!isAdmin) {
    return null;
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <header className="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div className="px-6 py-4 flex items-center justify-between">
          <div className="flex items-center gap-4">
            <div className="flex items-center gap-2">
              <div className="w-10 h-10 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center">
                <LayoutDashboard className="text-white" size={24} />
              </div>
              <div>
                <h1 className="text-xl font-bold text-gray-900">Dashboard Admin</h1>
                <p className="text-sm text-gray-500">Feminine Aura</p>
              </div>
            </div>
          </div>

          <div className="flex items-center gap-4">
            <div className="text-right">
              <p className="text-sm font-medium text-gray-900">{admin?.email}</p>
              <p className="text-xs text-gray-500">Administrateur</p>
            </div>
            <Button
              onClick={handleLogout}
              variant="outline"
              className="border-gray-300 hover:bg-red-50 hover:border-red-300 hover:text-red-600"
            >
              <LogOut size={18} className="mr-2" />
              Déconnexion
            </Button>
          </div>
        </div>
      </header>

      <div className="p-6">
        {/* Stats Cards */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <Card className="border-l-4 border-l-pink-500">
            <CardHeader className="pb-3">
              <CardDescription>Revenus du mois</CardDescription>
              <CardTitle className="text-3xl font-bold text-pink-600">
                {stats.totalRevenue.toFixed(2)}€
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="flex items-center gap-2 text-sm text-green-600">
                <TrendingUp size={16} />
                <span>+12% ce mois</span>
              </div>
            </CardContent>
          </Card>

          <Card className="border-l-4 border-l-blue-500">
            <CardHeader className="pb-3">
              <CardDescription>Commandes</CardDescription>
              <CardTitle className="text-3xl font-bold">{stats.totalOrders}</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="flex items-center gap-2 text-sm text-gray-600">
                <ShoppingCart size={16} />
                <span>Ce mois-ci</span>
              </div>
            </CardContent>
          </Card>

          <Card className="border-l-4 border-l-purple-500">
            <CardHeader className="pb-3">
              <CardDescription>Produits en stock</CardDescription>
              <CardTitle className="text-3xl font-bold">{stats.totalProducts}</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="flex items-center gap-2 text-sm text-gray-600">
                <Package size={16} />
                <span>Total produits</span>
              </div>
            </CardContent>
          </Card>

          <Card className="border-l-4 border-l-green-500">
            <CardHeader className="pb-3">
              <CardDescription>Nouveaux clients</CardDescription>
              <CardTitle className="text-3xl font-bold">{stats.newCustomers}</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="flex items-center gap-2 text-sm text-gray-600">
                <Users size={16} />
                <span>Ce mois-ci</span>
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Tabs Navigation */}
        <Tabs defaultValue="products" className="space-y-6">
          <TabsList className="bg-white p-1 shadow-sm">
            <TabsTrigger value="products" className="data-[state=active]:bg-pink-500 data-[state=active]:text-white">
              <Package className="mr-2" size={18} />
              Produits
            </TabsTrigger>
            <TabsTrigger value="orders" className="data-[state=active]:bg-pink-500 data-[state=active]:text-white">
              <ShoppingCart className="mr-2" size={18} />
              Commandes
            </TabsTrigger>
            <TabsTrigger value="categories" className="data-[state=active]:bg-pink-500 data-[state=active]:text-white">
              <Box className="mr-2" size={18} />
              Catégories
            </TabsTrigger>
          </TabsList>

          {/* Products Tab */}
          <TabsContent value="products">
            <Card>
              <CardHeader>
                <div className="flex items-center justify-between">
                  <div>
                    <CardTitle>Gestion des Produits</CardTitle>
                    <CardDescription>
                      Gérez votre catalogue de produits
                    </CardDescription>
                  </div>
                  <Dialog open={isProductDialogOpen} onOpenChange={setIsProductDialogOpen}>
                    <DialogTrigger asChild>
                      <Button
                        onClick={() => {
                          resetProductForm();
                          setIsProductDialogOpen(true);
                        }}
                        className="bg-pink-500 hover:bg-pink-600 text-white"
                      >
                        <Plus className="mr-2" size={18} />
                        Ajouter un produit
                      </Button>
                    </DialogTrigger>
                    <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
                      <DialogHeader>
                        <DialogTitle>
                          {editingProduct ? 'Modifier le produit' : 'Nouveau produit'}
                        </DialogTitle>
                        <DialogDescription>
                          Remplissez les informations du produit
                        </DialogDescription>
                      </DialogHeader>

                      <form onSubmit={handleProductSubmit} className="space-y-4">
                        <div className="grid grid-cols-2 gap-4">
                          <div>
                            <Label htmlFor="nom">Nom du produit *</Label>
                            <Input
                              id="nom"
                              value={productForm.nom}
                              onChange={(e) => setProductForm({ ...productForm, nom: e.target.value })}
                              required
                            />
                          </div>

                          <div>
                            <Label htmlFor="prix">Prix (€) *</Label>
                            <Input
                              id="prix"
                              type="number"
                              step="0.01"
                              value={productForm.prix}
                              onChange={(e) => setProductForm({ ...productForm, prix: e.target.value })}
                              required
                            />
                          </div>

                          <div>
                            <Label htmlFor="stock">Stock *</Label>
                            <Input
                              id="stock"
                              type="number"
                              value={productForm.stock}
                              onChange={(e) => setProductForm({ ...productForm, stock: e.target.value })}
                              required
                            />
                          </div>

                          <div>
                            <Label htmlFor="categorie">Catégorie *</Label>
                            <select
                              id="categorie"
                              value={productForm.id_categorie}
                              onChange={(e) => setProductForm({ ...productForm, id_categorie: e.target.value })}
                              className="w-full h-10 px-3 rounded-md border border-gray-300"
                              required
                            >
                              <option value="">Sélectionner...</option>
                              {categories.map((cat) => (
                                <option key={cat.id_categorie} value={cat.id_categorie}>
                                  {cat.nom}
                                </option>
                              ))}
                            </select>
                          </div>

                          <div>
                            <Label htmlFor="couleur">Couleur</Label>
                            <Input
                              id="couleur"
                              value={productForm.couleur}
                              onChange={(e) => setProductForm({ ...productForm, couleur: e.target.value })}
                            />
                          </div>

                          <div>
                            <Label htmlFor="taille">Taille</Label>
                            <select
                              id="taille"
                              value={productForm.taille}
                              onChange={(e) => setProductForm({ ...productForm, taille: e.target.value })}
                              className="w-full h-10 px-3 rounded-md border border-gray-300"
                            >
                              <option value="">Sélectionner...</option>
                              <option value="XS">XS</option>
                              <option value="S">S</option>
                              <option value="M">M</option>
                              <option value="L">L</option>
                              <option value="XL">XL</option>
                            </select>
                          </div>

                          <div>
                            <Label htmlFor="tissu">Tissu</Label>
                            <Input
                              id="tissu"
                              value={productForm.tissu}
                              onChange={(e) => setProductForm({ ...productForm, tissu: e.target.value })}
                            />
                          </div>

                          <div>
                            <Label htmlFor="image_url">URL de l'image</Label>
                            <Input
                              id="image_url"
                              value={productForm.image_url}
                              onChange={(e) => setProductForm({ ...productForm, image_url: e.target.value })}
                              placeholder="https://..."
                            />
                          </div>
                        </div>

                        <div>
                          <Label htmlFor="description">Description *</Label>
                          <Textarea
                            id="description"
                            value={productForm.description}
                            onChange={(e) => setProductForm({ ...productForm, description: e.target.value })}
                            rows={4}
                            required
                          />
                        </div>

                        <DialogFooter>
                          <Button
                            type="button"
                            variant="outline"
                            onClick={() => setIsProductDialogOpen(false)}
                          >
                            Annuler
                          </Button>
                          <Button type="submit" className="bg-pink-500 hover:bg-pink-600">
                            {editingProduct ? 'Modifier' : 'Créer'}
                          </Button>
                        </DialogFooter>
                      </form>
                    </DialogContent>
                  </Dialog>
                </div>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Image</TableHead>
                        <TableHead>Nom</TableHead>
                        <TableHead>Prix</TableHead>
                        <TableHead>Stock</TableHead>
                        <TableHead>Catégorie</TableHead>
                        <TableHead className="text-right">Actions</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {products.map((product) => (
                        <TableRow key={product.id_produit}>
                          <TableCell>
                            <img
                              src={product.image_url || '/placeholder.svg'}
                              alt={product.nom}
                              className="w-12 h-12 object-cover rounded"
                            />
                          </TableCell>
                          <TableCell className="font-medium">{product.nom}</TableCell>
                          <TableCell>{parseFloat(product.prix).toFixed(2)}€</TableCell>
                          <TableCell>
                            <Badge variant={product.stock < 5 ? 'destructive' : 'secondary'}>
                              {product.stock}
                            </Badge>
                          </TableCell>
                          <TableCell>{product.categorie_nom || 'N/A'}</TableCell>
                          <TableCell className="text-right">
                            <div className="flex justify-end gap-2">
                              <Button
                                size="sm"
                                variant="outline"
                                onClick={() => openEditDialog(product)}
                              >
                                <Edit size={16} />
                              </Button>
                              <Button
                                size="sm"
                                variant="outline"
                                className="text-red-600 hover:bg-red-50"
                                onClick={() => handleDeleteProduct(product.id_produit)}
                              >
                                <Trash2 size={16} />
                              </Button>
                            </div>
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Orders Tab */}
          <TabsContent value="orders">
            <Card>
              <CardHeader>
                <CardTitle>Gestion des Commandes</CardTitle>
                <CardDescription>
                  Consultez et gérez les commandes clients
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>N° Commande</TableHead>
                        <TableHead>Client</TableHead>
                        <TableHead>Total</TableHead>
                        <TableHead>Statut</TableHead>
                        <TableHead>Date</TableHead>
                        <TableHead className="text-right">Actions</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {orders.map((order) => (
                        <TableRow key={order.id}>
                          <TableCell className="font-mono">{order.numero}</TableCell>
                          <TableCell>{order.client}</TableCell>
                          <TableCell className="font-semibold">{order.total.toFixed(2)}€</TableCell>
                          <TableCell>
                            <Badge
                              variant={order.statut === 'confirmee' ? 'default' : 'secondary'}
                            >
                              {order.statut.replace('_', ' ')}
                            </Badge>
                          </TableCell>
                          <TableCell>{order.date}</TableCell>
                          <TableCell className="text-right">
                            <Button size="sm" variant="outline">
                              Voir détails
                            </Button>
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Categories Tab */}
          <TabsContent value="categories">
            <Card>
              <CardHeader>
                <CardTitle>Gestion des Catégories</CardTitle>
                <CardDescription>
                  Organisez vos produits par catégorie
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                  {categories.map((category) => (
                    <Card key={category.id_categorie}>
                      <CardHeader>
                        <CardTitle className="text-lg">{category.nom}</CardTitle>
                        <CardDescription className="text-sm">
                          {category.description}
                        </CardDescription>
                      </CardHeader>
                      <CardContent>
                        <div className="flex justify-between items-center">
                          <span className="text-sm text-gray-500">
                            Slug: {category.slug}
                          </span>
                          <div className="flex gap-2">
                            <Button size="sm" variant="outline">
                              <Edit size={14} />
                            </Button>
                          </div>
                        </div>
                      </CardContent>
                    </Card>
                  ))}
                </div>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </div>
  );
}
