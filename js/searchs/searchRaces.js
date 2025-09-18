function searchRaces() {
            const searchValue = document.getElementById('input-text-search').value.toLowerCase();
            const allRaces = document.querySelectorAll('.div-event-race');



                allRaces.forEach(race => {

                    const pText = race.querySelector('p')?.textContent.toLowerCase() || '';

                    const h4Text = race.querySelector('.div-race-info > h4')?.textContent.toLowerCase() || '';


                    if (pText.includes(searchValue) || h4Text.includes(searchValue)) {
                        race.style.display = '';

                    } else {
                        race.style.display = 'none';
                    }
                });
}


            
            document.getElementById('input-text-search').addEventListener('keydown', function(e) {

                if (e.key === 'Enter') {

                    searchRaces();
                }
            });

            document.getElementById('btn-submit-search').addEventListener('click', searchRaces);
