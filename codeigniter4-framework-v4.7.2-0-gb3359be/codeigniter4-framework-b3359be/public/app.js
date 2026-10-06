// V4 — On décrit l'interface à partir des données ; Vue s'occupe du DOM.
const { createApp, ref, computed, onMounted } = Vue;

const API = 'http://localhost:8000/api/formations';


const FormationPrice ={ template: "<h4>{{price}}</h4>"} 
// Un composant = un morceau d'interface réutilisable
const FormationCard = {

    setup(){
        const message = ref(1);
        return { message };
    },
    components: { FormationPrice },
    props: { formation: { type: Object, required: true } },
    template: `
        <article class="card">
            <h2>{{ formation.titre }}</h2>
            <p>{{ formation.description }}</p>
            <span class="badge">{{ formation.niveau }}</span>
            <h3>{{message}}</h3>
            <FormationPrice :price="formation.count" />
        </article>
    `
    // {{ }} échappe automatiquement : plus besoin de fonction e()
};

createApp({
    components: { FormationCard },

    setup() {
        const formations = ref([]);        // état : les données
        const chargement = ref(true);
        const erreur     = ref('');
        const filtre     = ref('Tous');
        const recherche =ref('');
        const niveaux    = ['Tous', 'L1', 'L2', 'L3'];


        //Etat du formulaire d ajout 
        const nouveauTitre       = ref('');
        const nouvelleDescription = ref('');
        const nouveauNiveau      = ref('');
        const envoiEnCours       = ref(false);
        const erreurAjout        = ref('');

        
        // Valeur calculée : recalculée dès que formations ou filtre change
        const formationsFiltrees = computed(() =>{

            return formations.value.filter(f=>{
                        const correspondNiveau =
            filtre.value === 'Tous' ||
            f.niveau === filtre.value;
                const corespondTitre =f.titre.toLowerCase().includes(recherche.value.trim().toLowerCase());
                return correspondNiveau && corespondTitre;
            });

        });

        const chargerFormations=async ()=>{

            try {
                const reponse =await fetch(API);
                if(!reponse.ok) throw new Error('HTTP'+reponse.status);

                formations.value = await reponse.json();
                
            } catch (error) {
                erreur.value='Erreur de chargement '+error.message;
                
            }
            finally{
                chargement.value=false;

            }
        };

        const ajouterFormation =async()=>{
            erreurAjout.value = '';
            envoiEnCours.value = true;


            try {
                const reponse = await fetch(API, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        titre: nouveauTitre.value,
                        description: nouvelleDescription.value,
                        niveau: nouveauNiveau.value
                    })
                });

                if (!reponse.ok) {
                    const data = await reponse.json();
                    throw new Error(data.erreur || 'Erreur lors de l\'ajout');
                }

                const nouvelleFormation = await reponse.json();
                formations.value.push(nouvelleFormation);

                // Réinitialisation des champs
                nouveauTitre.value = '';
                nouvelleDescription.value = '';
                nouveauNiveau.value = '';
            } catch (e) {
                erreurAjout.value = e.message;
            } finally {
                envoiEnCours.value = false;
            }


        };

      onMounted(chargerFormations);

        return { 
            formationsFiltrees, 
            chargement, 
            erreur, 
            filtre, 
            recherche, 
            niveaux,
            nouveauTitre,
            nouvelleDescription,
            nouveauNiveau,
            envoiEnCours,
            erreurAjout,
            ajouterFormation
        };
    }
}).mount('#app');
