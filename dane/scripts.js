const addCategoryBtn = document.getElementById('addCategoryBtn');
const modal = document.getElementById('categoryModal');
const closeModalBtn = document.getElementById('closeModal');
const categorySelect = document.getElementById('categorySelect');
const categoryForm = document.getElementById('categoryForm');

// Otwórz popup
addCategoryBtn.addEventListener('click', () => {
  modal.classList.remove('hidden');
  loadCategories();
});

// Zamknij popup
closeModalBtn.addEventListener('click', () => {
  modal.classList.add('hidden');
});

// Pobieranie kategorii z bazy (API)
async function loadCategories() {
  categorySelect.innerHTML = '<option value="">-- wybierz --</option>';

  try {
    const response = await fetch('/api/categories');
    const categories = await response.json();

    categories.forEach(cat => {
      const option = document.createElement('option');
      option.value = cat.id;
      option.textContent = cat.name;
      categorySelect.appendChild(option);
    });

  } catch (error) {
    console.error('Błąd pobierania kategorii:', error);
  }
}

// Obsługa formularza
categoryForm.addEventListener('submit', (e) => {
  e.preventDefault();

  const selectedCategoryId = categorySelect.value;

  if (!selectedCategoryId) return;

  console.log('Wybrana kategoria ID:', selectedCategoryId);

  // tutaj możesz:
  // - wysłać dane do backendu
  // - dodać kategorię do widoku
  // - zamknąć modal

  modal.classList.add('hidden');
});
