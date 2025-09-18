function searchMembers() {
                const searchValue = document.getElementById('input-text-search').value.toLowerCase();
                const allMembers = document.querySelectorAll('tr');

                allMembers.forEach((member, index) => {
                    if (index === 0) return;

                    const tds = member.querySelectorAll('td'); 




                    const fullText = Array.from(tds)
                                        .map(td => td.textContent.toLowerCase())
                                        .join(' ');




                    if (fullText.includes(searchValue)) {
                        member.style.display = '';
                    } else {
                        member.style.display = 'none';
                    }
                });
}




            document.getElementById('input-text-search').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') searchMembers();
            });

            document.getElementById('btn-submit-search').addEventListener('click', searchMembers);
