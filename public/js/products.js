$(document).ready(function () {
    // Add management
    $("#productForm").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "/products",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                location.reload();
            },
            error: function (xhr) {
                alert("Error adding product");
            },
        });
    });

    // Gestion de la modification
    $(".edit-btn").on("click", function () {
        let row = $(this).closest("tr");
        row.addClass("edit-mode");

        let name = row.find(".name").text();
        let quantity = row.find(".quantity").text();
        let price = row.find(".price").text();

        row.find(".name").html(
            `<input type="text" class="form-control" value="${name}">`
        );
        row.find(".quantity").html(
            `<input type="number" class="form-control" value="${quantity}">`
        );
        row.find(".price").html(
            `<input type="number" step="0.01" class="form-control" value="${price}">`
        );

        $(this).addClass("d-none");
        row.find(".save-btn, .cancel-btn").removeClass("d-none");
    });

    // Sauvegarde des modifications
    $(".save-btn").on("click", function () {
        let row = $(this).closest("tr");
        let id = row.data("id");

        let data = {
            name: row.find(".name input").val(),
            quantity: row.find(".quantity input").val(),
            price: row.find(".price input").val(),
            _token: $('meta[name="csrf-token"]').attr("content"),
        };

        $.ajax({
            url: `/products/${id}`,
            method: "PUT",
            data: data,
            success: function (response) {
                location.reload();
            },
            error: function (xhr) {
                alert("Error during modification");
            },
        });
    });

    // Annulation des modifications
    $(".cancel-btn").on("click", function () {
        location.reload();
    });
});
