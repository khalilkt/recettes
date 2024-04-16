$(document).ready(function () {
  // Code js ici !
});

function showEditElementForm(id) {
  $.ajax({
    type: "get",
    url: racine + "typesEquipements/EditElts/" + id,
    success: function (data) {
      $("#edit").html(data);
      $("#edit").show();
      $("#create").hide();
      $(".datatableshow2")
        .DataTable()
        .ajax.url($(".datatableshow2").attr("link") + "/" + id)
        .load();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function showEditCoordonneeForm(id) {
  $.ajax({
    type: "get",
    url: racine + "localites/editCoordonneeEd/" + id,
    success: function (data) {
      $("#edit").html(data);
      $("#edit").show();
      $("#create").hide();
      $(".datatableshow2")
        .DataTable()
        .ajax.url($(".datatableshow2").attr("link") + "/" + id)
        .load();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function editChoixElement(element) {
  saveform(element, function () {});
}
function editElement(element) {
  saveform(element, function () {
    $(".datatableshow2").DataTable().ajax.reload();
    $("#edit").hide();
  });
}

function createElementActeMDL(id) {
  $.ajax({
    type: "get",
    url: racine + "modeles/addElement/" + id,
    success: function (data) {
      $("#createElmtActe").html(data);
      $("#createElmtActe").show();
      $("#editElmtActe").hide();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function createCordonneeGPS(id) {
  $.ajax({
    type: "get",
    url: racine + "localites/ajoutCoordonnee/" + id,
    success: function (data) {
      $("#create").html(data);
      $("#create").show();
      $("#edit").hide();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function createElementType(id) {
  $.ajax({
    type: "get",
    url: racine + "typesEquipements/addElement/" + id,
    success: function (data) {
      $("#create").html(data);
      $("#create").show();
      $("#edit").hide();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function saveElement(element) {
  saveform(element, function (id) {
    $(".datatableshow2").DataTable().ajax.reload();
    $("#create").hide();
  });
}

function saveElementActe(element) {
  saveform(element, function (id) {
    $(".datatableshow2").DataTable().ajax.reload();
    $("#createElmtActe").hide();
  });
}
function natureElementEquipement() {
  var nature = $("#nature").val();
  if (nature == 1) {
    $("#varriable").show();
  } else {
    $("#varriable").hide();
    $("#divchoix").hide();
  }
}

function typeElementEquipement() {
  var type = $("#type").val();
  if (type == 3) {
    $("#divchoix").show();
  } else {
    $("#divchoix").hide();
  }
}

function showEquipements(cas) {
  if ($("#remplace").is(":checked") == true) {
    $('select[name="equipement_an"]').empty();
    $.ajax({
      type: "get",
      url: racine + "equipements/getEquipements",
      success: function (data) {
        if (data != "") {
          $("#list_eq").show();
          $('select[name="equipement_an"]').append(
            '<option  value=""></option>'
          );
          $.each(data, function (key, value) {
            $('select[name="equipement_an"]').append(
              '<option value="' + value.id + '"> ' + value.libelle + "</option>"
            );
          });
        }

        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  } else {
    $('select[name="equipement_an"]').empty();
    $("#list_eq").hide();
  }
}
function showPatrimoinePublic(cas) {
  if (cas == "add") {
    if ($("#patrimoine_public").is(":checked") == true) {
      $("#divNum_deliberation").show();
      $("#divDate_deliberation").show();
      $("#divDeliberation").addClass("col-md-12");
    } else {
      $("#divNum_deliberation").hide();
      $("#divDate_deliberation").hide();
      $("#divDeliberation").removeClass("col-md-12");
    }
  }
  if (cas == "edit") {
    if ($("#patrimoine_publicedit").is(":checked") == true) {
      $("#divNum_deliberationedit").show();
      $("#divDate_deliberationedit").show();
      $("#divDeliberationedit").addClass("col-md-12");
    } else {
      $("#divNum_deliberationedit").hide();
      $("#divDate_deliberationedit").hide();
      $("#divDeliberationedit").removeClass("col-md-12");
    }
  }
}

function get_elements() {
  var type = $("#typeEq").val();
  $("#elements_type").html(loading_content);
  $.ajax({
    type: "get",
    url: racine + "equipements/getElts/" + type,
    success: function (data) {
      $("#elements_type").html("");
      $("#elements_type").html(data);
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function disableinputs() {
  $("#libelle").attr("disabled", true);
  $("#libelle_ar").attr("disabled", true);
  $("#code").attr("disabled", true);
  $("#date_acquisition").attr("disabled", true);
  $("#secteur").attr("disabled", true);
  $("#localite").attr("disabled", true);
  $("#patrimoine_publicedit").attr("disabled", true);
  $("#num_deliberation").attr("disabled", true);
  $("#date_deliberation").attr("disabled", true);
  $("#deliberation").attr("disabled", true);
  $("#latitude").attr("disabled", true);
  $("#longitude").attr("disabled", true);
  $("#eau").attr("disabled", true);
  $("#electricite").attr("disabled", true);
  $("#hygiene").attr("disabled", true);
  $("#acessibilite").attr("disabled", true);
  $("#situation_en").attr("disabled", true);
  $("#btnsave").attr("disabled", true);
}

function showinputs() {
  $("#libelle").attr("disabled", false);
  $("#libelle_ar").attr("disabled", false);
  $("#code").attr("disabled", false);
  $("#date_acquisition").attr("disabled", false);
  $("#secteur").attr("disabled", false);
  $("#localite").attr("disabled", false);
  $("#patrimoine_publicedit").attr("disabled", false);
  $("#num_deliberation").attr("disabled", false);
  $("#date_deliberation").attr("disabled", false);
  $("#deliberation").attr("disabled", false);
  $("#latitude").attr("disabled", false);
  $("#longitude").attr("disabled", false);
  $("#eau").attr("disabled", false);
  $("#electricite").attr("disabled", false);
  $("#hygiene").attr("disabled", false);
  $("#acessibilite").attr("disabled", false);
  $("#situation_en").attr("disabled", false);
  $("#btnsave").attr("disabled", false);
}

function getElementType() {
  var type = $("#typeE").val();
  var oldtype = $("#oldtype").val();
  if (type != oldtype) {
    $("#showelements").html(loading_content);
    $.ajax({
      type: "get",
      url: racine + "equipements/getElt/" + type,
      success: function (data) {
        $("#showelements").html("");
        $("#showelements").html(data);
        showinputs();
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  } else {
    alert("Deja la meme type d'equipement");
    disableinputs();
    var type = $("#id").val();
    $.ajax({
      type: "get",
      url: racine + "equipements/getEltexistent/" + type,
      success: function (data) {
        $("#showelements").html("");
        $("#showelements").html(data);
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function createbBudget() {
  $.ajax({
    type: "get",
    url: racine + "finances/add/",
    success: function (data) {
      $("#addbudget").html(data);
      $("#addbudget").show();
      $("#showbudget").hide();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function createbBatiment(id) {
  $("#createBatiment").html(loading_content);
  $.ajax({
    type: "get",
    url: racine + "equipements/addBatiment/" + id,
    success: function (data) {
      $("#createBatiment").html(data);
      $("#createBatiment").show();
      $("#editBatiment").hide();
      $("#changeBatiment").hide();
      $("#afficheBatiment").hide();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}
function createSuivi(id) {
  $("#createItemsSuivi").html(loading_content);
  $.ajax({
    type: "get",
    url: racine + "equipements/plans/addSuivi/" + id,
    success: function (data) {
      $("#createItemsSuivi").html(data);
      $("#createItemsSuivi").show();
      $("#editItemsSuivi").hide();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function createItemsPlan(id) {
  $.ajax({
    type: "get",
    url: racine + "equipements/plans/addItem/" + id,
    success: function (data) {
      $("#createItemsPlan").html(data);
      $("#createItemsPlan").show();
      $("#editItemsPlan").hide();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}
function createPlan(id) {
  $.ajax({
    type: "get",
    url: racine + "equipements/addPlan/" + id,
    success: function (data) {
      $("#createPlan").html(data);
      $("#createPlan").show();
      $("#editPlan").hide();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function saveItemPlan(element) {
  saveform(element, function (id) {
    $(".datatableshow4").DataTable().ajax.reload();
    $("#createItemsPlan").hide();
  });
}

function saveBidgetInitial(element) {
  saveform(element, function (id) {
    location.reload();
  });
}

function updateBidget(element) {
  var container = $(element).attr("container");
  $(element).attr("disabled", "disabled");
  $(".main-icon").hide();
  $(".spinner-border").show();
  saveform(element, function (id) {
    $(".main-icon").show();
    $(".spinner-border").hide();
    $("#btn_valider").show();
    $("#btn_valider1").show();
  });
}

function valideBidget(element, id) {
  var confirme = confirm("Êtes-vous sûr de valider cet budget");
  if (confirme) {
    var container = $(element).attr("container");
    $(element).attr("disabled", "disabled");
    $("#main-icon").hide();
    $("#spinner-border").show();
    $.ajax({
      type: "get",
      url: racine + "finances/valideBg/" + id,
      success: function (data) {
        location.reload();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function devalideBidget(element, id) {
  var confirme = confirm("Êtes-vous sûr de devalider cet budget");
  if (confirme) {
    var container = $(element).attr("container");
    $(element).attr("disabled", "disabled");
    $(".main-icon").hide();
    $(".spinner-border").show();
    $.ajax({
      type: "get",
      url: racine + "finances/devalideBg/" + id,
      success: function (data) {
        location.reload();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function cloturerBidget(element, annee) {
  var confirme = confirm("Êtes-vous sûr de cloturer  cet budget");
  if (confirme) {
    var container = $(element).attr("container");
    $(element).attr("disabled", "disabled");
    $(".main-icon").hide();
    $(".spinner-border").show();
    $.ajax({
      type: "get",
      url: racine + "finances/clotureBg/" + annee,
      success: function (data) {
        location.reload();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function saveSuiviItem(element) {
  saveform(element, function (id) {
    $(".datatableshow5").DataTable().ajax.reload();
    $("#createItemsSuivi").hide();
  });
}

function saveBatiment(element) {
  saveform(element, function (id) {
    $(".datatableshow2").DataTable().ajax.reload();
    $("#createBatiment").hide();
  });
}

function savePlan(element) {
  saveform(element, function (id) {
    $(".datatableshow3").DataTable().ajax.reload();
    $("#createPlan").hide();
  });
}
function editBatiment(element) {
  saveform(element, function (id) {
    $(".datatableshow2").DataTable().ajax.reload();
    $("#editBatiment").hide();
  });
}

function changeBatiment(element) {
  saveform(element, function (id) {
    $(".datatableshow2").DataTable().ajax.reload();
    $("#changeBatiment").hide();
  });
}

function saveEcriture(element) {
  saveform(element, function (id) {});
}
function updateEcriture(element) {
  saveform(element, function (id) {
    $(".datatableshow2").DataTable().ajax.reload();
    $("#editEcriture").hide();
  });
}

function saveTypeEquipemet(element) {
  saveform(element, function (id) {});
}

function editbudget(element) {
  saveform(element, function (id) {
    location.reload();
  });
}

function editPlan(element) {
  saveform(element, function (id) {
    $(".datatableshow3").DataTable().ajax.reload();
    $("#editPlan").hide();
  });
}

function editItemPlan(element) {
  saveform(element, function (id) {
    $(".datatableshow4").DataTable().ajax.reload();
    $("#editItemsPlan").hide();
  });
}

function editSuiviItem(element) {
  saveform(element, function (id) {
    $(".editSuiviItem5").DataTable().ajax.reload();
    $("#editItemsSuivi").hide();
  });
}

function showEditBatimentForm(id) {
  $.ajax({
    type: "get",
    url: racine + "equipements/editBatiments/" + id,
    success: function (data) {
      $("#editBatiment").html(data);
      $("#editBatiment").show();
      $("#createBatiment").hide();
      $("#changeBatiment").hide();
      $("#afficheBatiment").hide();
      $(".datatableshow2")
        .DataTable()
        .ajax.url($(".datatableshow2").attr("link") + "/" + id)
        .load();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}
function showEditBatimentForm2(id) {
  $.ajax({
    type: "get",
    url: racine + "equipements/editBatiments/" + id,
    success: function (data) {
      $("#editBatiment1").html(data);
      $("#editBatiment1").show();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function annulerRole(id) {
  var confirme = confirm(
    "Êtes-vous sûr de vouloir annuler les enregistrements de ce role"
  );
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "contribuables/annulerRole/" + id,
      success: function (data) {
        alert("Bien supprimer");
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function changerBatimentForm(id) {
  $("#changeBatiment").html(loading_content);
  $.ajax({
    type: "get",
    url: racine + "equipements/changeBatiments/" + id,
    success: function (data) {
      $("#changeBatiment").html(data);
      $("#changeBatiment").show();
      $("#afficheBatiment").hide();
      $("#editBatiment").hide();
      $("#createBatiment").hide();
      $(".datatableshow2")
        .DataTable()
        .ajax.url($(".datatableshow2").attr("link") + "/" + id)
        .load();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function suiviprogrammes() {
  fichier = $("#fichier").val();
  if (fichier != "") {
    var confirme = confirm(
      "Êtes-vous sûr de vouloir importer cet enregistrement"
    );
    if (confirme) {
      document.formstpdf.action = "contribuables/openficherexcel";
      document.formstpdf.target = "_blank"; // Open in a new window
      document.formstpdf.submit(); // Submit the page
      return true;
    }
  }
}

function importerEXCELEMP() {
  fichier = $("#fichier").val();
  //alert(fichier)
  if (fichier != "") {
    var confirme = confirm(
      "Êtes-vous sûr de vouloir importer cet enregistrement"
    );
    if (confirme) {
      document.formstpdf.action = "contribuables/openficherexcel";
      document.formstpdf.target = "_blank"; // Open in a new window
      document.formstpdf.submit(); // Submit the page
      return true;
    }
  } else {
    alert("Selectionner un fichier");
  }
  /*  document.addForm.action = "contribuables/openficherexcel";
    document.addForm.target = "_blank";
    document.addForm.submit();*/
  /* document.form1.action = 'contribuables/openficherexcel/'+fichier;
    document.form1.target = "_blank";    // Open in a new window
    document.form1.submit(); */ // Submit the page
}

function visualiserproblem(id) {
  alert("y");
  var confirme = confirm("Êtes-vous sûr de vouloir visualiser ces problemes");
  if (confirme) {
    document.formst1.action = "contribuables/visualiserproblem/" + id;
    document.formst1.target = "_blank"; // Open in a new window
    document.formst1.submit(); // Submit the page
    return true;
  }
}
function exporterListeprotocolEch() {
  document.form1l.action = "contribuables/exporterListeprotocolEch";
  document.form1l.target = "_blank"; // Open in a new window
  document.form1l.submit(); // Submit the page
}
function visualiserEchangeEquipement(id) {
  $("#afficheBatiment").html(loading_content);
  $.ajax({
    type: "get",
    url: racine + "equipements/afficheBatiment/" + id,
    success: function (data) {
      $("#afficheBatiment").html(data);
      $("#afficheBatiment").show();
      $("#changeBatiment").hide();
      $("#editBatiment").hide();
      $("#createBatiment").hide();
      $(".datatableshow2")
        .DataTable()
        .ajax.url($(".datatableshow2").attr("link") + "/" + id)
        .load();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function showEditPlanForm(id) {
  $.ajax({
    type: "get",
    url: racine + "equipements/EditPlans/" + id,
    success: function (data) {
      $("#editPlan").html(data);
      $("#editPlan").show();
      $("#createPlan").hide();
      $(".datatableshow2")
        .DataTable()
        .ajax.url($(".datatableshow2").attr("link") + "/" + id)
        .load();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function closeDivItemPlan() {
  $("#divItemPlan").hide();
  $("#divitems").show();
}

function openImgContainer() {
  $(".image-div").toggle("slow");
}

function suiviItemPlan(id) {
  $.ajax({
    type: "get",
    url: racine + "equipements/plans/suiviItem/" + id,
    success: function (data) {
      $("#divItemPlan").html(data);
      $("#divItemPlan").show();
      $("#divitems").hide();
      $("#editItemsPlan").hide();
      $("#createItemsPlan").hide();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function showEditItem(id) {
  $.ajax({
    type: "get",
    url: racine + "equipements/plans/editItem/" + id,
    success: function (data) {
      $("#editItemsPlan").html(data);
      $("#editItemsPlan").show();
      $("#createItemsPlan").hide();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function showEditSuivi(id) {
  $.ajax({
    type: "get",
    url: racine + "equipements/plans/editSuivi/" + id,
    success: function (data) {
      $("#editItemsSuivi").html(data);
      $("#editItemsSuivi").show();
      $("#createItemsSuivi").hide();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}
function deleteBatiment(id) {
  var confirme = confirm(
    "Êtes-vous sûr de vouloir supprimer cet enregistrement"
  );
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "equipements/DeleteBatiment/" + id,
      success: function (data) {
        $(".datatableshow2").DataTable().ajax.reload();
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}
function fermercontribuable(id) {
  var confirme = confirm("Êtes-vous sûr de fermer  ce contribuable");
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "contribuables/fermercontribuable/" + id,
      success: function (data) {
        // $('.datatableshow2').DataTable().ajax.reload();
        alert("Bien ferme");
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}
function annulerProtocol(id) {
  var confirme = confirm(
    "Êtes-vous sûr de vouloir supprimer cet enregistrement"
  );
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "contribuables/annulerProtocol/" + id,
      success: function (data) {
        $(".datatableshow2").DataTable().ajax.reload();
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function annulerPayement(id) {
  var confirme = confirm(
    "Êtes-vous sûr de vouloir supprimer cet enregistrement"
  );
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "contribuables/annulerPayement/" + id,
      success: function (data) {
        alert("bien suprimer");
        $(".datatableshow3").DataTable().ajax.reload();
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function deleteElement(id) {
  var confirme = confirm(
    "Êtes-vous sûr de vouloir supprimer cet enregistrement"
  );
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "typesEquipements/deleteElement/" + id,
      success: function (data) {
        $(".datatableshow2").DataTable().ajax.reload();
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function deleteCoordonness(id, msg) {
  var confirme = confirm(msg);
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "localites/deleteCoordonnees/" + id,
      success: function (data) {
        $(".datatableshow2").DataTable().ajax.reload();
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function deleteEcriture(id, sens) {
  var confirme = confirm(
    "Êtes-vous sûr de vouloir supprimer cet enregistrement"
  );
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "finances/deleteEcriture/" + id + "/" + sens,
      success: function (data) {
        $(".datatableshow2").DataTable().ajax.reload();
        $("#editEcriture").hide();
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function deleteElementModele(id) {
  var confirme = confirm(
    "Êtes-vous sûr de vouloir supprimer cet enregistrement"
  );
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "modeles/deleteElement/" + id,
      success: function (data) {
        $(".datatableshow2").DataTable().ajax.reload();
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}
function deletePlan(id) {
  var confirme = confirm(
    "Êtes-vous sûr de vouloir supprimer cet enregistrement"
  );
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "equipements/DeletePlan/" + id,
      success: function (data) {
        $(".datatableshow3").DataTable().ajax.reload();
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function deleteItem(id) {
  $("#editItemsPlan").hide();
  $("#createItemsPlan").hide();
  var confirme = confirm(
    "Êtes-vous sûr de vouloir supprimer cet enregistrement"
  );
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "equipements/plans/deleteItem/" + id,
      success: function (data) {
        $(".datatableshow4").DataTable().ajax.reload();
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function deleteSuivi(id) {
  $("#editItemsSuivi").hide();
  $("#createItemsSuivi").hide();
  var confirme = confirm(
    "Êtes-vous sûr de vouloir supprimer cet enregistrement"
  );
  if (confirme) {
    $.ajax({
      type: "get",
      url: racine + "equipements/plans/deleteSuivi/" + id,
      success: function (data) {
        $(".datatableshow5").DataTable().ajax.reload();
      },
      error: function (data) {
        alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}
function showchoixElement(id, lemodule, tab = 3, largeModal = "lg") {
  $.ajax({
    type: "get",
    url: racine + lemodule + "/showChoiceValue/" + id,
    success: function (data) {
      $("#second-modal .modal-dialog").addClass("modal-" + largeModal);
      $("#second-modal .modal-header-body").html(data);
      $("#second-modal").modal();
      setMainTabs(tab, true);
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function showEditEcritureForm(id, sen) {
  lemodule = "finances/editEcritures";
  openObjectModal(id, lemodule);
  $("#main-modal :input").inputmask();
  /*$.ajax({
        type: 'get',
        url: racine + 'finances/EditEcriture/'+id+'/'+sen,
        success: function (data) {
            $("#editEcriture").html(data);
            $("#editEcriture").show();
            $('.datatableshow2').DataTable().ajax.url($(".datatableshow2").attr('link') + "/" + id).load();
            resetInit();
            $("#second-modal :input").inputmask();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });*/
}

function getbudget(id) {
  url = racine + "finances/getbudget/" + id;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#add-modal .modal-dialog").addClass("modal-lg");

      $("#add-modal .modal-header-body").html(data);
      $("#add-modal").modal();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function suiviExecution(id) {
  url = racine + "finances/suiviExecution/" + id;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#second-modal .modal-dialog").addClass("modal-lg");

      $("#second-modal .modal-header-body").html(data);
      $("#second-modal").modal();
      resetInit();
      $("#second-modal :input").inputmask();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function situationBudget(id) {
  url = racine + "finances/situationBudget/" + id;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#second-modal .modal-dialog").addClass("modal-lg");

      $("#second-modal .modal-header-body").html(data);
      $("#second-modal").modal();
      resetInit();
      $("#second-modal :input").inputmask();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}
function addEcriture(id) {
  lemodule = "finances/ecritures";
  openObjectModal(id, lemodule);
  /*url = racine + 'finances/getEcriture/'+id;
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");

            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
            setMainTabs(tab);
            $("#second-modal :input").inputmask();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });*/
}

function historiqueEcriture(id) {
  url = racine + "finances/historiqueEcriture/" + id;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#second-modal .modal-dialog").addClass("modal-lg");

      $("#second-modal .modal-header-body").html(data);
      $("#second-modal").modal();
      resetInit();
      $("#second-modal :input").inputmask();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function visualiserBudget(id) {
  url = racine + "finances/visualiserBudget/" + id;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#add-modal .modal-dialog").addClass("modal-xl");
      $("#add-modal .modal-header-body").html(data);
      $("#add-modal").modal();
      resetInit();
      $("#add-modal :input").inputmask();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function visualiserFicheComunne() {
  url = racine + "equipements/visualiserCommune/";
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#main-modal .modal-dialog").addClass("modal-lg");
      $("#main-modal .modal-header-body").html(data);
      $("#main-modal").modal();
      resetInit();
      $("#add-modal :input").inputmask();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function suiviHistorique(id) {
  url = racine + "equipements/suiviHistorique/" + id;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#add-modal .modal-dialog").addClass("modal-lg");
      $("#add-modal .modal-header-body").html(data);
      $("#add-modal").modal();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function suiviHistoriqueBatiment(id) {
  url = racine + "equipements/suiviHistoriqueBt/" + id;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#second-modal .modal-dialog").addClass("modal-lg");
      $("#second-modal .modal-header-body").html(data);
      $("#second-modal").modal();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}
function modifierTypeEquipement(id) {
  url = racine + "equipements/modifierTypeEquipement/" + id;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#add-modal .modal-dialog").addClass("modal-lg");
      $("#add-modal .modal-header-body").html(data);
      $("#add-modal").modal();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function openOldbudget(annee) {
  url = racine + "finances/openOldbudget/" + annee;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#add-modal .modal-dialog").addClass("modal-xl");
      $("#add-modal .modal-header-body").html(data);
      $("#add-modal").modal();
      resetInit();
      $(":input").inputmask();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function openMaintenanceModal(id, lemodule, tab = 11, largeModal = "xl") {
  $.ajax({
    type: "get",
    url: racine + lemodule + "/get/" + id,
    success: function (data) {
      $("#second-modal .modal-dialog").addClass("modal-" + largeModal);
      $("#second-modal .modal-header-body").html(data);
      $("#second-modal").modal();
      setMainTabs(tab, true);
      $("#second-modal").attr("link");
      $("#datatableshow")
        .DataTable()
        .ajax.url($("#datatableshow").attr("link") + "/" + id)
        .load();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function exportPDFBatiment(id) {
  alert(id);
  $.ajax({
    type: "get",
    url: racine + "equipements/exportPDFBatiment/" + id,
    success: function (data) {},
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function excelEquipement1() {
  type = $("#typeEquipement").val();
  secteur = $("#secteurEquipement").val();
  localite = $("#localiteEquipement").val();
  if (localite == "") {
    localite = "all";
  }
  if (secteur == "") {
    secteur = "all";
  }
  if (type == "") {
    type = "all";
  }

  document.formst.action =
    "equipements/exportExcel/" + type + "/" + secteur + "/" + localite + "";
  document.formst.target = "_blank"; // Open in a new window
  document.formst.submit(); // Submit the page
  return true;
}

function imprimerFicheCommunal() {
  equipement = 0;
  employe = 0;
  budget = 0;
  contribuables = 0;
  archive = 0;
  actes = 0;
  localites = 0;
  annee = $("#annee").val();
  if ($("#equipement").is(":checked") == true) {
    equipement = 1;
  }
  if ($("#employe").is(":checked") == true) {
    employe = 1;
  }
  if ($("#budget").is(":checked") == true) {
    budget = 1;
  }
  if ($("#contribuables").is(":checked") == true) {
    contribuables = 1;
  }
  if ($("#archive").is(":checked") == true) {
    archive = 1;
  }
  if ($("#actes").is(":checked") == true) {
    actes = 1;
  }
  if ($("#localites").is(":checked") == true) {
    localites = 1;
  }

  document.formcom.action =
    "equipements/exportPDFFicheCommune/" +
    equipement +
    "/" +
    employe +
    "/" +
    budget +
    "/" +
    contribuables +
    "/" +
    archive +
    "/" +
    actes +
    "/" +
    localites +
    "/" +
    annee +
    "";
  document.formcom.target = "_blank"; // Open in a new window
  document.formcom.submit(); // Submit the page
  return true;
}

function activeAnneBdg() {
  if ($("#budget").is(":checked") == true) {
    $("#anneeBdg").show();
  } else {
    $("#anneeBdg").hide();
  }
}

function desactivecf() {
  if ($("#rolePATENTE").is(":checked") == true) {
    $("#fichier").attr("disabled", false);
    document.getElementById("rolecf").checked = false;
  } else {
    $("#fichier").attr("disabled", true);
  }
}

function desactivepa() {
  if ($("#rolecf").is(":checked") == true) {
    document.getElementById("rolePATENTE").checked = false;
    $("#fichier").attr("disabled", false);
  } else {
    $("#fichier").attr("disabled", true);
  }
}
function pdfBudget(id, etat) {
  niveau_affichage = "all";
  classe = 0;
  if (etat == 1) {
    document.formbdg.action =
      "finances/exportPDFBudgetFiltre/" +
      id +
      "/" +
      niveau_affichage +
      "/" +
      classe +
      "";
    document.formbdg.target = "_blank"; // Open in a new window
    document.formbdg.submit(); // Submit the page
  }
  if (etat == 2) {
    document.formbdg1.action =
      "finances/exportPDFBudgetFiltre/" +
      id +
      "/" +
      niveau_affichage +
      "/" +
      classe +
      "";
    document.formbdg1.target = "_blank"; // Open in a new window
    document.formbdg1.submit(); // Submit the page
  }
  if (etat == 3) {
    niveau_affichage = $("#niveau_affichage").val();
    classe = $("#classe").val();
    if (classe == "all" || classe == "") {
      classe = 0;
    }
    document.formbdg2.action =
      "finances/exportPDFBudgetFiltre/" +
      id +
      "/" +
      niveau_affichage +
      "/" +
      classe +
      "";
    document.formbdg2.target = "_blank"; // Open in a new window
    document.formbdg2.submit(); // Submit the page
  }
  return true;
}

function excelBudget(id, etat) {
  let niveau_affichage = "all";
  classe = 0;
  if (etat == 1) {
    document.formbdg.action =
      "finances/exportEXCELBudgetFiltre/" +
      id +
      "/" +
      niveau_affichage +
      "/" +
      classe +
      "";
    document.formbdg.target = "_blank"; // Open in a new window
    document.formbdg.submit(); // Submit the page
  }
  if (etat == 2) {
    document.formbdg1.action =
      "finances/exportEXCELBudgetFiltre/" +
      id +
      "/" +
      niveau_affichage +
      "/" +
      classe +
      "";
    document.formbdg1.target = "_blank"; // Open in a new window
    document.formbdg1.submit(); // Submit the page
  }
  if (etat == 3) {
    niveau_affichage = $("#niveau_affichage").val();
    classe = $("#classe").val();
    if (classe == "all" || classe == "") {
      classe = 0;
    }
    document.formbdg2.action =
      "finances/exportEXCELBudgetFiltre/" +
      id +
      "/" +
      niveau_affichage +
      "/" +
      classe +
      "";
    document.formbdg2.target = "_blank"; // Open in a new window
    document.formbdg2.submit(); // Submit the page
  }
  return true;
}

function exporterSuiviExecution(element) {
  date1 = $("#date1").val();
  date2 = $("#date2").val();
  id = $("#id").val();
  sence = $("#sence").val();
  texte = $("#texte").val();
  niveau = $("#niveau").val();
  if (texte == "") {
    texte = "all";
  }
  if ($("#detail").is(":checked") == true) {
    detail = 1;
  } else {
    detail = 0;
  }
  if (date1 == "" && date2 == "") {
    date1 = date2 = "all";
  }
  document.formst.action =
    "finances/exporterSuiviExecution/" +
    id +
    "/" +
    date1 +
    "/" +
    date2 +
    "/" +
    sence +
    "/" +
    texte +
    "/" +
    detail +
    "/" +
    niveau +
    "";
  document.formst.target = "_blank"; // Open in a new window
  document.formst.submit(); // Submit the page
  return true;
}
function exporterSuiviExecutionExcel(element) {
  date1 = $("#date1").val();
  date2 = $("#date2").val();
  id = $("#id").val();
  sence = $("#sence").val();
  texte = $("#texte").val();
  niveau = $("#niveau").val();
  if (texte == "") {
    texte = "all";
  }
  if ($("#detail").is(":checked") == true) {
    detail = 1;
  } else {
    detail = 0;
  }
  if (date1 == "" && date2 == "") {
    date1 = date2 = "all";
  }
  document.formst.action =
    "finances/exporterSuiviExecutionExcel/" +
    id +
    "/" +
    date1 +
    "/" +
    date2 +
    "/" +
    sence +
    "/" +
    texte +
    "/" +
    detail +
    "/" +
    niveau +
    "";
  document.formst.target = "_blank"; // Open in a new window
  document.formst.submit(); // Submit the page
  return true;
}

function exporterSituationBudgetaire(element) {
  date1 = $("#date1").val();
  date2 = $("#date2").val();
  id = $("#id").val();
  type = $("#type").val();

  if ($("#detail").is(":checked") == true) {
    detail = 1;
  } else {
    detail = 0;
  }
  if (date1 == "" && date2 == "") {
    date1 = date2 = "all";
  }
  document.formst.action =
    "finances/exporterSituationBudgetaire/" +
    id +
    "/" +
    date1 +
    "/" +
    date2 +
    "/" +
    type +
    "/" +
    detail +
    "";
  document.formst.target = "_blank"; // Open in a new window
  document.formst.submit(); // Submit the page
  return true;
}

function exporterSituationBudgetaireExcel(element) {
  date1 = $("#date1").val();
  date2 = $("#date2").val();
  id = $("#id").val();
  type = $("#type").val();

  if ($("#detail").is(":checked") == true) {
    detail = 1;
  } else {
    detail = 0;
  }
  if (date1 == "" && date2 == "") {
    date1 = date2 = "all";
  }
  document.formst.action =
    "finances/exporterSituationBudgetaireExcel/" +
    id +
    "/" +
    date1 +
    "/" +
    date2 +
    "/" +
    type +
    "/" +
    detail +
    "";
  document.formst.target = "_blank"; // Open in a new window
  document.formst.submit(); // Submit the page
  return true;
}

function pdfEquipement() {
  type = $("#typeEquipement").val();
  secteur = $("#secteurEquipement").val();
  localite = $("#localiteEquipement").val();
  if (localite == "") {
    localite = "all";
  }
  if (secteur == "") {
    secteur = "all";
  }
  if (type == "") {
    type = "all";
  }
  document.formst.action =
    "equipements/exportPDF/" + type + "/" + secteur + "/" + localite + "";
  document.formst.target = "_blank"; // Open in a new window
  document.formst.submit(); // Submit the page
  return true;
}

function cartesEquipement() {
  document.formsteq.action = "carte/";
  document.formsteq.target = "_blank"; // Open in a new window
  document.formsteq.submit(); // Submit the page
  return true;
}

function filterTypePayement() {
  type = $("#type_payement").val();

  $("#datatableshow")
    .DataTable()
    .ajax.url(racine + "contribuables/getDT/" + type)
    .load();
}

function chnageStatsRole(roleId) {}

function changeStatsYear() {
  // change the window location to /?year=year
  year = $("#stats_year_select").val();
  window.location = racine + "?annee=" + year;
}

function changeSelectedRole(roleId) {
  url = new URL(window.location.href);

  if (!roleId) {
    url.searchParams.delete("role");
  } else {
    url.searchParams.set("role", roleId);
  }
  window.location = url.href;
}

function changeSelectedStatsDate() {
  selectedMonth = $("#stats_month_select").val();
  selectedDay = $("#stats_day_select").val();

  url = new URL(window.location.href);
  url.searchParams.set("mois", selectedMonth);
  url.searchParams.set("jour", selectedDay);
  if (selectedMonth == null) {
    url.searchParams.delete("mois");
    url.searchParams.delete("jour");
  } else if (selectedDay == null) {
    url.searchParams.delete("jour");
  }
  window.location = url.href;
}

function nextDayOrMonth(next = true) {
  url = new URL(window.location.href);
  selectedYear = url.searchParams.get("annee");
  selectedMonth = url.searchParams.get("mois");
  selectedDay = url.searchParams.get("jour");
  didChangeYear = false;

  if (!selectedMonth) {
    return;
  }

  if (selectedDay == null) {
    selectedMonth = parseInt(selectedMonth) + (next ? 1 : -1);
    if (selectedMonth > 12) {
      selectedMonth = 1;
      selectedYear = parseInt(selectedYear) + 1;
      didChangeYear = true;
    }
    if (selectedMonth < 1) {
      selectedMonth = 12;
      selectedYear = parseInt(selectedYear) - 1;
      didChangeYear = true;
    }
  } else {
    date = new Date(selectedYear, selectedMonth, selectedDay);
    date.setDate(date.getDate() + (next ? 1 : -1));
    if (selectedYear != date.getFullYear()) {
      didChangeYear = true;
    }
    selectedYear = date.getFullYear();
    selectedMonth = date.getMonth();
    selectedDay = date.getDate();
  }
  url.searchParams.set("annee", selectedYear);
  url.searchParams.set("mois", selectedMonth);
  url.searchParams.set("jour", selectedDay);

  if (didChangeYear) {
    url.searchParams.delete("role");
  }
  window.location = url.href;
}

function filterSecteurEquipement() {
  secteur = $("#secteurEquipement").val();
  type = $("#typeEquipement").val();
  localite = $("#localiteEquipement").val();
  if (localite == "") {
    localite = "all";
  }
  if (type == "") {
    type = "all";
  }
  $("#datatableshow")
    .DataTable()
    .ajax.url(
      racine + "equipements/getDTE/" + type + "/" + secteur + "/" + localite
    )
    .load();
}

function filterLocaliteEquipement() {
  secteur = $("#secteurEquipement").val();
  type = $("#typeEquipement").val();
  localite = $("#localiteEquipement").val();
  if (secteur == "") {
    secteur = "all";
  }
  if (type == "") {
    type = "all";
  }
  $("#datatableshow")
    .DataTable()
    .ajax.url(
      racine + "equipements/getDTE/" + type + "/" + secteur + "/" + localite
    )
    .load();
}

function exportequipementPDF(id) {
  document.formspdf.action = "equipements/exportequipementPDF/" + id + "";
  document.formspdf.target = "_blank"; // Open in a new window
  document.formspdf.submit(); // Submit the page
  return true;
}

function exportactePDF(id) {
  document.formspdf.action = "actes/exportactePDF/" + id + "";
  document.formspdf.target = "_blank"; // Open in a new window
  document.formspdf.submit(); // Submit the page
  return true;
}
//for collapse icon
$(document).ready(function () {
  // Add minus icon for collapse element which is open by default
  $(".collapse.show").each(function () {
    $(this)
      .prev(".card-header")
      .find(".fa")
      .addClass("fa-minus")
      .removeClass("fa-plus");
  });

  // Toggle plus minus icon on show hide of collapse element
  $(".collapse")
    .on("show.bs.collapse", function () {
      $(this)
        .prev(".card-header")
        .find(".fa")
        .removeClass("fa-plus")
        .addClass("fa-minus");
    })
    .on("hide.bs.collapse", function () {
      $(this)
        .prev(".card-header")
        .find(".fa")
        .removeClass("fa-minus")
        .addClass("fa-plus");
    });
});

function calculrecursive(id, bdg_id) {
  id_parent = $("#val_" + bdg_id + "" + id).attr("id_parent");
  //alert(id_parent)

  var somme = 0;
  $("input[id_parent=" + id_parent + "]").each(function () {
    //alert($(this).val().trim());
    somme += parseFloat($(this).val().replace(/ /g, ""));
  });
  $("#val_" + bdg_id + "" + id_parent + "").val(somme);
  if (id_parent != 0) {
    calculrecursive(id_parent, bdg_id);
  }
}

function calculeparent(id_parent) {}

function calculrecursive2(idelement) {
  id_parent = $(element).attr("id_parent");
  id = $(element).attr("id");
  //calculeparent(id_parent);
}

function filtrebudgets(id) {
  var niveau_affichage = $("#niveau_affichage").val();
  var classe = $("#classe").val();
  if (classe == "all" || classe == "") {
    classe = 0;
  }
  $.ajax({
    type: "get",
    url:
      racine +
      "finances/BudgetFiltre/" +
      id +
      "/" +
      niveau_affichage +
      "/" +
      classe,
    success: function (data) {
      $("#presentation").html("");
      $("#presentation").html(data);
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function showEditElementFormActe(id) {
  $.ajax({
    type: "get",
    url: racine + "modeles/EditElts/" + id,
    success: function (data) {
      $("#editElmtActe").html(data);
      $("#editElmtActe").show();
      $("#createElmtActe").hide();
      $(".datatableshow2")
        .DataTable()
        .ajax.url($(".datatableshow2").attr("link") + "/" + id)
        .load();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function editElementActe(element) {
  saveform(element, function () {
    $(".datatableshow2").DataTable().ajax.reload();
    $("#editElmtActe").hide();
  });
}

function get_elements_acte() {
  // $("#elements_modele").html(loading_content);

  var modele = $("#modele_acte").val();
  $.ajax({
    type: "get",
    url: racine + "actes/getElts/" + modele,
    success: function (data) {
      //alert(data)
      $("#elements_modele").html("");
      $("#elements_modele").html(data);
      $("#elements_modele").show();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function filterActeModele() {
  modele = $("#modele").val();
  date1 = $("#date1").val();
  date2 = $("#date2").val();
  //alert(date1+''+date2)
  if (date1 == "" || date2 == "") {
    date1 = date2 = "all";
  }

  $("#datatableshow")
    .DataTable()
    .ajax.url(racine + "actes/getDT/" + modele + "/" + date1 + "/" + date2)
    .load();
}

function filterActeModeleDate() {
  modele = $("#modele").val();
  date1 = $("#date1").val();
  date2 = $("#date2").val();
  //alert(date1+''+date2)
  if (modele == "") {
    modele = "all";
  } else {
  }

  if (date1 != "" && date2 != "") {
    $("#datatableshow")
      .DataTable()
      .ajax.url(racine + "actes/getDT/" + modele + "/" + date1 + "/" + date2)
      .load();
  }
}

function parentElements(id, premiere, dernier, apres) {
  par = $("#parent").val();
  if (par != 0) {
    $("#alignement").hide();
    $.ajax({
      type: "get",
      url: racine + "modeles/getFilstLigne/" + id + "/" + par,
      success: function (data) {
        $("#ordres").show();
        $('select[name="ordres"]').empty();
        if (data != "") {
          $('select[name="ordres"]').append('<option  value=""></option>');
          $('select[name="ordres"]').append(
            '<option  value="0">' + premiere + "</option>"
          );
          $.each(data, function (key, value) {
            $('select[name="ordres"]').append(
              '<option value="' +
                value.ordre +
                '">' +
                apres +
                ' "' +
                value.content_value +
                '"</option>'
            );
          });
          $('select[name="ordres"]').append(
            '<option  value="D" selected>' + dernier + "</option>"
          );
        } else {
          $('select[name="ordres"]').append(
            '<option  value="P">' + premiere + "</option>"
          );
        }

        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  } else {
    $("#alignement").show();
    ligne = $("#ligne").val();
    $.ajax({
      type: "get",
      url: racine + "modeles/getParentLigne/" + id + "/" + ligne,
      success: function (data) {
        $('select[name="ordres"]').empty();
        if (data != "") {
          $('select[name="ordres"]').append('<option value=""></option>');
          $('select[name="ordres"]').append(
            '<option  value="0">' + premiere + "</option>"
          );
          $.each(data, function (key, value) {
            $('select[name="ordres"]').append(
              '<option value="' +
                value.ordre +
                '">' +
                apres +
                ' "' +
                value.content_value +
                '"</option>'
            );
          });
          $('select[name="ordres"]').append(
            '<option value="D" selected>' + dernier + "</option>"
          );
        } else {
          $('select[name="ordres"]').append(
            '<option  value="P" >' + premiere + "</option>"
          );
        }
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}
function parentElementsedit(id, premiere, dernier, apres) {
  par = $("#parentedit").val();
  if (par != 0) {
    $("#alignementedit").hide();
    $.ajax({
      type: "get",
      url: racine + "modeles/getFilstLigne/" + id + "/" + par,
      success: function (data) {
        $("#ordresedit").show();
        $("#ordresedit").empty();
        if (data != "") {
          $("#ordresedit").append('<option  value=""></option>');
          $("#ordresedit").append(
            '<option  value="0">' + premiere + "</option>"
          );
          $.each(data, function (key, value) {
            $("#ordresedit").append(
              '<option value="' +
                value.ordre +
                '">' +
                apres +
                ' "' +
                value.content_value +
                '"</option>'
            );
          });
          $("#ordresedit").append(
            '<option  value="D" selected>' + dernier + "</option>"
          );
        } else {
          $("#ordresedit").append(
            '<option  value="P">' + premiere + "</option>"
          );
        }

        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  } else {
    $("#alignementedit").show();
    ligne = $("#ligneedit").val();
    $.ajax({
      type: "get",
      url: racine + "modeles/getParentLigne/" + id + "/" + ligne,
      success: function (data) {
        $("#ordresedit").empty();
        if (data != "") {
          $("#ordresedit").append('<option value=""></option>');
          $("#ordresedit").append(
            '<option  value="0">' + premiere + "</option>"
          );
          $.each(data, function (key, value) {
            $("#ordresedit").append(
              '<option value="' +
                value.ordre +
                '">' +
                apres +
                ' "' +
                value.content_value +
                '"</option>'
            );
          });
          $("#ordresedit").append(
            '<option value="D" selected>' + dernier + "</option>"
          );
        } else {
          $("#ordresedit").append(
            '<option  value="P" >' + premiere + "</option>"
          );
        }
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function parentLigne(
  id,
  premiere,
  dernier,
  apres,
  suite_elemet,
  pas_de_parent
) {
  ligne = $("#ligne").val();
  position = $("#position").val();
  /*$('select option')
        .filter(function() {
            return !this.value || $.trim(this.value).length == 0;
        })
        .remove();*/
  $('select[name="parent"]').empty();
  if (ligne != "" || ligne != 0) {
    $.ajax({
      type: "get",
      url: racine + "modeles/getParentLigne/" + id + "/" + ligne,
      success: function (data) {
        $("#alignement").show();
        $("#ordres").show();
        if (position != "<br>") {
          $('select[name="ordres"]').empty();
        }
        $('select[name="parent"]').append(
          '<option  value="0">' + pas_de_parent + "</option>"
        );
        if (data != "") {
          if (position != "<br>") {
            $('select[name="ordres"]').append('<option  value=""></option>');
            $('select[name="ordres"]').append(
              '<option  value="0">' + premiere + "</option>"
            );
          }
          $.each(data, function (key, value) {
            $('select[name="parent"]').append(
              '<option value="' +
                value.id +
                '">' +
                suite_elemet +
                '  "' +
                value.content_value +
                '"</option>'
            );
            if (position != "<br>") {
              $('select[name="ordres"]').append(
                '<option value="' +
                  value.ordre +
                  '">' +
                  apres +
                  ' "' +
                  value.content_value +
                  '"</option>'
              );
            }
          });
          if (position != "<br>") {
            $('select[name="ordres"]').append(
              '<option  value="D" selected>' + dernier + "</option>"
            );
          }
        } else {
          $('select[name="ordres"]').append(
            '<option  value="P">' + premiere + "</option>"
          );
        }
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  } else {
  }
}
function parentLigneedit(
  id,
  premiere,
  dernier,
  apres,
  suite_elemet,
  pas_de_parent
) {
  ligne = $("#ligneedit").val();
  position = $("#positionedit").val();
  $("#parentedit").empty();
  if (ligne != "" || ligne != 0) {
    $.ajax({
      type: "get",
      url: racine + "modeles/getParentLigne/" + id + "/" + ligne,
      success: function (data) {
        $("#alignementedit").show();
        $("#ordresedit").show();
        if (position != "<br>") {
          $("#ordresedit").empty();
        }
        $("#parentedit").append(
          '<option  value="0">' + pas_de_parent + "</option>"
        );
        if (data != "") {
          if (position != "<br>") {
            $("#ordresedit").append('<option  value=""></option>');
            $("#ordresedit").append(
              '<option  value="0">' + premiere + "</option>"
            );
          }
          $.each(data, function (key, value) {
            $("#parentedit").append(
              '<option value="' +
                value.id +
                '">' +
                suite_elemet +
                '  "' +
                value.content_value +
                '"</option>'
            );
            if (position != "<br>") {
              $("#ordresedit").append(
                '<option value="' +
                  value.ordre +
                  '">' +
                  apres +
                  ' "' +
                  value.content_value +
                  '"</option>'
              );
            }
          });
          if (position != "<br>") {
            $("#ordresedit").append(
              '<option  value="D" selected>' + dernier + "</option>"
            );
          }
        } else {
          $("#ordresedit").append(
            '<option  value="P">' + premiere + "</option>"
          );
        }
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  } else {
  }
}
function positionElement(id, premiere, dernier, apres) {
  position = $("#position").val();
  if (position == "<br>") {
    $.ajax({
      type: "get",
      url: racine + "modeles/getLignes/" + id,
      success: function (data) {
        $('select[name="ligne"]').empty();
        if (data != "") {
          $('select[name="ligne"]').append('<option value=""></option>');
          $('select[name="ligne"]').append(
            '<option  value="0">' + premiere + "</option>"
          );
          $.each(data, function (key, value) {
            $('select[name="ligne"]').append(
              '<option value="' +
                value.ligne +
                '">' +
                apres +
                ' "' +
                value.content_value +
                '" </option>'
            );
          });

          $('select[name="ligne"]').append(
            '<option value="D" selected>' + dernier + "</option>"
          );
        } else {
          $('select[name="ligne"]').append(
            '<option  value="P">' + premiere + "</option>"
          );
        }
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
    $("#saut_ligne").show();
    $("#alignement").show();
    $("#divparent").hide();
    $('select[name="ordres"]').empty();
    $('select[name="ordres"]').append(
      '<option  value="P">' + premiere + "</option>"
    );
  } else {
    $.ajax({
      type: "get",
      url: racine + "modeles/getLignes/" + id,
      success: function (data) {
        $('select[name="ligne"]').empty();
        if (data != "") {
          $('select[name="ligne"]').append('<option  value=""></option>');
          $.each(data, function (key, value) {
            $('select[name="ligne"]').append(
              '<option value="' +
                value.ligne +
                '"> ' +
                value.content_value +
                "</option>"
            );
          });
        } else {
          $('select[name="ligne"]').append(
            '<option  value="P">' + premiere + "</option>"
          );
        }
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
    $("#niveau_ligne").hide();
    $('select[name="ligne"]').append('<option value="" selected></option>');
    $("#saut_ligne").hide();
    $("#alignement").hide();
    $("#divparent").show();
  }
}

function positionElementedit(id, premiere, dernier, apres) {
  position = $("#positionedit").val();
  if (position == "<br>") {
    $.ajax({
      type: "get",
      url: racine + "modeles/getLignes/" + id,
      success: function (data) {
        $("#ligneedit").empty();
        if (data != "") {
          $("#ligneedit").append('<option value=""></option>');
          $("#ligneedit").append(
            '<option  value="0">' + premiere + "</option>"
          );
          $.each(data, function (key, value) {
            $("#ligneedit").append(
              '<option value="' +
                value.ligne +
                '">' +
                apres +
                ' "' +
                value.content_value +
                '" </option>'
            );
          });

          $("#ligneedit").append(
            '<option value="D" selected>' + dernier + "</option>"
          );
        } else {
          $("#ligneedit").append(
            '<option  value="P">' + premiere + "</option>"
          );
        }
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
    $("#saut_ligneedit").show();
    $("#alignementedit").show();
    $("#divparentedit").hide();
    $("#ordresedit").empty();
    $("#ordresedit").append('<option  value="P">' + premiere + "</option>");
  } else {
    $.ajax({
      type: "get",
      url: racine + "modeles/getLignes/" + id,
      success: function (data) {
        $("#ligneedit").empty();
        if (data != "") {
          $("#ligneedit").append('<option  value=""></option>');
          $.each(data, function (key, value) {
            $("#ligneedit").append(
              '<option value="' +
                value.ligne +
                '"> ' +
                value.content_value +
                "</option>"
            );
          });
        } else {
          $("#ligneedit").append(
            '<option  value="P">' + premiere + "</option>"
          );
        }
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
    $("#niveau_ligneedit").hide();
    $("#ligneedit").append('<option value="" selected></option>');
    $("#saut_ligneedit").hide();
    $("#alignementedit").hide();
    $("#divparentedit").show();
  }
}

function getMotantCategorie() {
  id = $("#categorie").val();
  $.ajax({
    type: "get",
    url: racine + "forchets/getCategorie/" + id,
    success: function (data) {
      if (data != "") {
        $.each(data, function (key, value) {
          if (value.id == id) {
            $("#montant").val("" + value.montant + "");
          }
        });
      } else {
        $("#montant").val("0");
      }
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}
function montantActivite() {
  id = $("#activite_id").val();
  emplacement = $("#emplacement").val();
  taille = $("#taille").val();
  if (id != "" && emplacement != "" && taille != "") {
    $.ajax({
      type: "get",
      url:
        racine +
        "contribuables/getActvite/" +
        id +
        "/" +
        emplacement +
        "/" +
        taille,
      success: function (data) {
        if (data != "") {
          $.each(data, function (key, value) {
            $("#montant").val("" + value.montant + "");
          });
        } else {
          $("#montant").val("0");
        }
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}
function montantActiviteedit() {
  id = $("#activite_idedit").val();
  emplacement = $("#emplacementedit").val();
  taille = $("#tailleedit").val();
  if (id != "" && emplacement != "" && taille != "") {
    $.ajax({
      type: "get",
      url:
        racine +
        "contribuables/getActvite/" +
        id +
        "/" +
        emplacement +
        "/" +
        taille,
      success: function (data) {
        if (data != "") {
          $.each(data, function (key, value) {
            $("#montantedit").val("" + value.montant + "");
          });
        } else {
          $("#montantedit").val("0");
        }
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}

function typePayementFin() {
  type = $("#typePayement").val();
  if (type == 1) {
    decativerTours();
  }
  if (type == 2) {
    decativerTours();
    $("#banquediv").show();
    $("#comptediv").show();
    $("#banque").show();
    $("#compte").show();
  }
  if (type == 4) {
    decativerTours();
    $("#banquediv").show();
    $("#numChequediv").show();
    $("#banque").show();
    $("#numCheque").show();
  }
  if (type == 5) {
    decativerTours();
    $("#nom_appdiv").show();
    $("#teldiv").show();
    $("#nom_app").show();
    $("#tel").show();
  }
  if (type == 6) {
    decativerTours();
    $("#nom_degrevement").show();
    $("#decision").show();
    $("#aplay").show();
    $("#fichier").show();
  }
}
function decativerTours() {
  $("#banquediv").hide();
  $("#comptediv").hide();
  $("#numChequediv").hide();
  $("#nom_appdiv").hide();
  $("#teldiv").hide();
  $("#banque").hide();
  $("#compte").hide();
  $("#numCheque").hide();
  $("#nom_app").hide();
  $("#tel").hide();

  $("#nom_degrevement").hide();
  $("#decision").val("");
  $("#aplay").hide();
  $("#fichier").hide();
  // $("#banque").val('');
  $('select[name="banque"]').append('<option  value="" selected></option>');
  $("#compte").val("");
  $("#numCheque").val("");
  // $("#nom_app").val('');
  $('select[name="nom_app"]').append('<option  value="" selected></option>');
  $("#tel").val("");
}
function changerAnnee() {
  annee = $("#annee").val();
  if (annee != "") {
    $.ajax({
      type: "get",
      url: racine + "contribuables/changeAnnee/" + annee,
      success: function (data) {
        location.reload();
        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  }
}
function recuperemontant() {
  protocol = $("#protocol").val();
  echance = $("#echeances_id").val();
  if (echance != "") {
    if (echance != "all") {
      $.ajax({
        type: "get",
        url:
          racine + "contribuables/recuperemontant1/" + protocol + "/" + echance,
        success: function (data) {
          // alert(data)
          $("#montantPotocol").val(data);
          $("#max_essaymontant").val(data);
          resetInit();
        },
        error: function () {
          $.alert(
            "Une erreur est survenue veuillez réessayer ou actualiser la page!"
          );
        },
      });
    } else {
      $.ajax({
        type: "get",
        url: racine + "contribuables/recuperemontant/" + protocol,
        success: function (data) {
          // alert(data)
          $("#montantPotocol").val(data);
          $("#max_essaymontant").val(data);
          resetInit();
        },
        error: function () {
          $.alert(
            "Une erreur est survenue veuillez réessayer ou actualiser la page!"
          );
        },
      });
    }
  }
}
function montantMax() {
  montant = $("#montantPotocol").val().replace(/ /g, "");

  max_essay = $("#max_essaymontant").val();

  if (parseFloat(montant) > parseFloat(max_essay)) {
    alert("dsl le montant est grand");
    $("#montantPotocol").val("");
  } else if (montant == 0) {
    alert("dsl le montant est grand");
    $("#montantPotocol").val("");
  } else {
  }
}

function montantMaxMax() {
  montant = $("#montantSaisi").val().replace(/ /g, "");

  max_essay = $("#max_essay").val();

  if (parseFloat(montant) > parseFloat(max_essay)) {
    alert("dsl le montant est grand");
    $("#montantSaisi").val($("#max_essay").val());
  } else if (montant == 0) {
    alert("dsl le montant est grand");
    $("#montantSaisi").val($("#max_essay").val());
  } else {
  }
}

function desactivernbre() {
  nbreEch = $("#nbreEch").val();
  $("#div2ech").hide();
  $("#div3ech").hide();
  $("#div4ech").hide();
  $("#div5ech").hide();
  $("#date2").val("");
  $("#date3").val("");
  $("#date4").val("");
  $("#date5").val("");
  if (nbreEch != 1) {
    // alert(nbreEch)
    for (var i = 1; i <= nbreEch; i++) {
      $("#div" + i + "ech").show();
    }
  }
}
function montantMaxDoit() {
  montant = $("#montantSaisi").val().replace(/ /g, ""); //

  max_essay = $("#max_essay").val();
  montantMaximun = parseFloat(max_essay) - parseFloat(montant);
  if (montantMaximun < 0) {
    alert("dsl le montant est grand");
    $("#montantSaisi").val("");
  } else if (montant == 0) {
    alert("dsl le montant est null");
    $("#montantSaisi").val("");
  } else {
  }
}

function newPayementPv(id, montant) {
  $("#create1").html(loading_content);
  $.ajax({
    type: "get",
    url: racine + "contribuables/newPayementPv/" + id,
    success: function (data) {
      $("#create1").html(data);
      $("#create1").show();
      $("#id").val(id);
      $("#mt").val(montant);
      $("#edit1").hide();
      resetInit();
      $(":input").inputmask();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}
function newPayement(id, montant) {
  $("#create1").html(loading_content);
  $.ajax({
    type: "get",
    url: racine + "contribuables/newPayement/" + id,
    success: function (data) {
      $("#create1").html(data);
      $("#create1").show();
      $("#id").val(id);
      $("#mt").val(montant);
      $("#edit1").hide();
      resetInit();
      $(":input").inputmask();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function newprotpcol(id, montant) {
  $.ajax({
    type: "get",
    url: racine + "contribuables/newprotpcol/" + id,
    success: function (data) {
      $("#create").html(data);
      $("#create").show();
      $("#id").val(id);
      $("#mt").val(montant);
      $("#edit").hide();
      resetInit();
      $(":input").inputmask();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function ajouterContrAuProgDuJour(contrId) {
  url = racine + "contribuables/ajouterContrAuProgDuJour/" + contrId;

  $.ajax({
    type: "get",
    url: url,

    success: function (data) {
      $("#second-modal .modal-dialog").addClass("modal-xl");
      $("#second-modal .modal-header-body").html(data);
      $("#second-modal").modal();

      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function payercontibiable(
  annee,
  contribuableId = null,
  contribuableName = null
) {
  type_payement = $("#type_payement").val();
  if (type_payement != "all") {
    url =
      racine + "contribuables/payercontibiable/" + annee + "/" + type_payement;
    $.ajax({
      type: "get",
      url: url,

      success: function (data) {
        $("#second-modal .modal-dialog").addClass("modal-xl");
        $("#second-modal .modal-header-body").html(data);
        $("#second-modal").modal();

        resetInit();

        if (contribuableId && contribuableName) {
          $("#contribuable").append(
            $("<option>", {
              value: contribuableId.toString(),
              text: contribuableName,
            })
          );

          $("#contribuable").val(contribuableId.toString());
          $("#contribuable").trigger("change");
          $("#contribuable").prop("disabled", true);
        }
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  } else {
    alert("Selectionner un role !");
  }
}
function suiviContibuable(annee) {
  url = racine + "contribuables/suiviContibuable/" + annee;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#second-modal .modal-dialog").addClass("modal-xl");
      $("#second-modal .modal-header-body").html(data);
      $("#second-modal").modal();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function suspension(id) {
  url = racine + "contribuables/suspension/" + id;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#second-modal .modal-dialog").addClass("modal-lg");
      $("#second-modal .modal-header-body").html(data);
      $("#second-modal").modal();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function reprendrePayement(id, id_pay) {
  $("#divContenueplaysup").html(loading_content);
  $.ajax({
    type: "get",
    url: racine + "contribuables/reprendrePayement/" + id + "/" + id_pay,
    success: function (data) {
      $("#divContenueplaysup").html("");
      $("#divContenueplaysup").html(data);
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function suspendrePayement(id, id_pay) {
  $("#divContenueplaysup").html(loading_content);
  $.ajax({
    type: "get",
    url: racine + "contribuables/suspendrePayement/" + id + "/" + id_pay,
    success: function (data) {
      $("#divContenueplaysup").html("");
      $("#divContenueplaysup").html(data);
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function playsup(id) {
  url = racine + "contribuables/playsup/" + id;
  $.ajax({
    type: "get",
    url: url,
    success: function (data) {
      $("#second-modal .modal-dialog").addClass("modal-lg");
      $("#second-modal .modal-header-body").html(data);
      $("#second-modal").modal();
      resetInit();
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function addContrToProgramme(contrId) {
  programmeId = $("#programmes").val();
  url = racine + "programmes/addToGrouping/" + contrId + "/" + programmeId;
  $.ajax({
    type: "post",
    url: url,
    success: function (data) {
      alert("Contribuable ajouté au programme avec succès");
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function savePayement(element) {
  saveform(element, function (id) {
    alert("Bien enregistre");
    $(".datatableshow3").DataTable().ajax.reload();
    $("#create").hide();
  });
}
function saveProtocol(element) {
  saveform(element, function (id) {
    $(".datatableshow2").DataTable().ajax.reload();
    $("#create").hide();
  });
}

function saveSuspension(element) {
  saveform(element, function (id) {});
}

function onDecouvrementMonthChange(annee) {
  selectedMonth = $("#contr_created_at_select").val();
  $.ajax({
    type: "get",
    url:
      racine +
      "contribuables/get_contrubiable_count/" +
      annee +
      "/" +
      selectedMonth,
    success: function (data) {
      $("#contr_split_select").find("option").remove();

      var newOptions = [{ value: 1, text: "1 - 500" }];

      for (var i = 500; i <= data; i += 500) {
        newOptions.push({
          value: i / 500 + 1,
          text: i + " - " + (i + 500),
        });
      }

      $.each(newOptions, function (index, option) {
        $("#contr_split_select").append(
          $("<option>", {
            value: option.value,
            text: option.text,
          })
        );
      });

      // Refresh the selectpicker to reflect the changes
      $("#contr_split_select").selectpicker("refresh");

      $("#contr_total_resut").text(data.toString());
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function filterContribuable(annee) {
  $filtrage = $("#filtrage").val();
  if ($filtrage == 3) {
    // hide contr_created_at_select
    $("#div_contr_created_at").show();
  } else {
    $("#div_contr_created_at").hide();
  }
  contribuable = $("#contribuable").val();
  date1 = date2 = "all";
  $("#datatableshow2")
    .DataTable()
    .ajax.url(
      racine +
        "contribuables/getPayementAnnne/" +
        annee +
        "/" +
        contribuable +
        "/" +
        date1 +
        "/" +
        date2
    )
    .load();
}

function montantArticleContribuable() {
  article = $("#article").val();
  $.ajax({
    type: "get",
    url: racine + "contribuables/montantArticleContribuable/" + article,
    success: function (data) {
      $("#montantSaisi").val(data);
      $("#max_essay").val(data);
    },
    error: function () {
      $.alert(
        "Une erreur est survenue veuillez réessayer ou actualiser la page!"
      );
    },
  });
}

function selectionnerContribuable(annee) {
  contribuable = $("#contribuable").val();
  if (contribuable != "") {
    $.ajax({
      type: "get",
      url: racine + "contribuables/selectionnerContribuable/" + contribuable,
      success: function (data) {
        if (data == 0) {
          //role
          newPayementPv(contribuable, 3);
        }
        if (data == 1) {
          //protocole
          newPayement(contribuable, 3);
        }

        resetInit();
      },
      error: function () {
        $.alert(
          "Une erreur est survenue veuillez réessayer ou actualiser la page!"
        );
      },
    });
  } else {
    $('select[name="protocole"]').empty();
    $("#divprotocole").hide();
    $('select[name="article"]').empty();
    $("#divarticle").hide();
  }
}

function filterContribuableMois(annee) {
  contribuable = $("#contribuable").val();
  date1 = date2 = "all";
  mois = $("#mois").val();
  if (contribuable == "") {
    contribuable = "all";
  } else {
  }

  $("#datatableshow2")
    .DataTable()
    .ajax.url(
      racine +
        "contribuables/getPayementAnnne/" +
        annee +
        "/" +
        contribuable +
        "/" +
        date1 +
        "/" +
        date2
    )
    .load();
}

function filterContribuableDate(annee) {
  date1 = $("#date1").val();
  contribuable = $("#contribuable").val();
  date2 = $("#date2").val();

  //alert(date1+''+date2)
  if (contribuable == "") {
    contribuable = "all";
  } else {
  }

  if (date1 != "" && date2 != "") {
    $("#datatableshow2")
      .DataTable()
      .ajax.url(
        racine +
          "contribuables/getPayementAnnne/" +
          annee +
          "/" +
          contribuable +
          "/" +
          date1 +
          "/" +
          date2
      )
      .load();
  }
}

function pdfSuiviPayementCtb(annee) {
  date1 = $("#date1").val();
  contr_created_at_month = $("#contr_created_at_select").val() ?? 1;
  selection_split = $("#contr_split_select").val() ?? 1;

  filtrage = $("#filtrage").val();
  date2 = $("#date2").val();
  role = $("#type_payement").val();

  if (date1 == "" || date2 == "") {
    date1 = "all";
    date2 = "all";
  }
  document.formst.action =
    "contribuables/pdfSuiviPayementCtb/" +
    annee +
    "/" +
    filtrage +
    "/" +
    date1 +
    "/" +
    date2 +
    "/" +
    role +
    "/" +
    contr_created_at_month +
    "/" +
    selection_split;
  document.formst.target = "_blank"; // Open in a new window
  document.formst.submit(); // Submit the page
  return true;
}

function excelSuiviPayementCtb(annee) {
  contr_created_at_month = $("#contr_created_at_select").val() ?? 1;
  selection_split = $("#contr_split_select").val() ?? 1;

  date1 = $("#date1").val();
  contribuable = $("#contribuable").val();
  date2 = $("#date2").val();
  filtrage = $("#filtrage").val();

  if (contribuable == "") {
    contribuable = "all";
  }
  if (date1 == "" || date2 == "") {
    date1 = "all";
    date2 = "all";
  }

  document.formst.action =
    "contribuables/excelSuiviPayementCtb/" +
    annee +
    "/" +
    contribuable +
    "/" +
    date1 +
    "/" +
    date2 +
    "/" +
    filtrage +
    "/" +
    contr_created_at_month +
    "/" +
    selection_split;

  document.formst.target = "_blank"; // Open in a new window
  document.formst.submit(); // Submit the page
  return true;
}
function exportcontribuablePDF(id) {
  document.formspdf.action = "contribuables/exportcontribuablePDF/" + id + "";
  document.formspdf.target = "_blank"; // Open in a new window
  document.formspdf.submit(); // Submit the page
  return true;
}

function exportprogrammePDF(id) {
  document.formspdf.action = "programmes/exportprogrammePDF/" + id + "";
  document.formspdf.target = "_blank"; // Open in a new window
  document.formspdf.submit(); // Submit the page
  return true;
}

function sutiationcontribuablePDF(id) {
  annee = $("#annee").val();
  if (annee != "") {
    document.formspdf.action =
      "contribuables/sutiationcontribuablePDF/" + id + "/" + annee;
    document.formspdf.target = "_blank"; // Open in a new window
    document.formspdf.submit(); // Submit the page
    return true;
  } else {
    alert("Selectionner l annee");
  }
}
function fichdefermercontribuable(id) {
  annee = $("#annee").val();
  if (annee != "") {
    document.formspdf.action = "contribuables/fichdefermercontribuable/" + id;
    document.formspdf.target = "_blank"; // Open in a new window
    document.formspdf.submit(); // Submit the page
    return true;
  } else {
    alert("Selectionner l annee");
  }
}

function imprimerModele(id) {
  document.formspdf.action = "modeles/imprimerModele/" + id + "";
  document.formspdf.target = "_blank"; // Open in a new window
  document.formspdf.submit(); // Submit the page
  return true;
}
$(":input").inputmask();
