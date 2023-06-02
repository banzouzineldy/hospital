data.utilisateur.forEach(element => {
    document.getElementById('rdvs_emailsmedecin').innerHTML+= `<option value="${element.id}">${element.nom}+${element.prenom}</option>`
    
});
champselect_doctor.classList.remove('d-none');



