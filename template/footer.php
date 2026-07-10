        </main>

        <footer class="mt-5 py-3 text-center text-muted" style="border-top: 1px solid #dee2e6; font-size:13px;">
            Gestão Franquia NBA &copy; <?= date('Y') ?>
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"/>
        ></script>


        <script>
        $("#table_id").DataTable({
                "pageLength":5,
                    lengthMenu:[
                        [3,10,25,50],
                        [3,10,25,50]
        ],
        "language":{
            "url":"https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-PT.json"
            }
        }
        );

        //alerta para conclusao das acoes criar editar e eliminar
        </script>
        <?php if (isset($_SESSION['alerta'])): ?>
            <script>
                Swal.fire({
                    icon: '<?php echo $_SESSION['alerta']['icon']; ?>',
                    title: '<?php echo $_SESSION['alerta']['title']; ?>',
                    text: '<?php echo $_SESSION['alerta']['text']; ?>',
                    confirmButtonColor: '#17408B' // Azul NBA para o botão de OK
                });
            </script>
            <?php 
            // Apaga a mensagem para não aparecer novamente no refresh
            unset($_SESSION['alerta']); 
            ?>
        <?php endif; ?>
    </body>
</html>
