@extends('layouts.app')

@section('content')
    {{-- addStudent Modal --}}
    <div class="modal fade" id="AddStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Alerta de campo não preenchido / exibe as mensagens de erro aqui --}}
                    <ul id="saveform_errList"></ul>

                    <div class="form-group mb-3">
                        <label for="">Name</label>
                        <input type="text" class="name form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Email</label>
                        <input type="text" class="email form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Phone</label>
                        <input type="text" class="phone form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Course</label>
                        <input type="text" class="course form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_student">Save</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End addStudent Moldal --}}

    {{-- EditStudentModal --}}
    <div class="modal fade" id="EditStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <!-- alterações -->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit & Update Student</h5><!-- alterações -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Alerta de campo não preenchido / exibe as mensagens de erro --}}
                    <ul id="updateform_errList"></ul><!-- alterações -->
                    <!-- inclusão para recuperação do id (hidden -> não aparece pro usuário) -->
                    <input type="hidden" id="edit_stud_id">

                    <div class="form-group mb-3">
                        <label for="">Name</label>
                        <input type="text" id="edit_name" class="name form-control">
                        <!-- alterações nos inputs incluindo: id="edit_name" -->
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Email</label>
                        <input type="text" id="edit_email" class="email form-control"><!-- alterações -->
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Phone</label>
                        <input type="text" id="edit_phone" class="phone form-control"><!-- alterações -->
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Course</label>
                        <input type="text" id="edit_course" class="course form-control"><!-- alterações -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_student">Update</button><!-- alterações -->
                </div>
            </div>
        </div>
    </div>
    {{-- End EditStudentModal --}}


    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                {{-- Menssagem de aviso --}}
                <div id="success_message"></div>

                <div class="card">
                    <div class="card-header">
                        <h4>Students Data
                            <a href="#" data-bs-toggle="modal" data-bs-target="#AddStudentModal"
                                class="btn btn-primary float-end btn-sm">Add Student</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        {{-- Table Students --}}
                        <table class="table table-bordered table-stripe">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Course</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Dados do AJAX aqui --}}
                            </tbody>
                        </table>
                        {{-- End table Students --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Buscando informações e populando o dataTables Student
            fetchstudent();

            function fetchstudent() {
                $.ajax({
                    type: "GET",
                    url: "/fetch-students",
                    dataType: "json",
                    success: function(response) {
                        //console.log(response.students);
                        $('tbody').html("");
                        $.each(response.students, function(key, item) {
                            $('tbody').append('<tr>\
                                        <td>' + item.id + '</td>\
                                        <td>' + item.name + '</td>\
                                        <td>' + item.email + '</td>\
                                        <td>' + item.phone + '</td>\
                                        <td>' + item.course + '</td>\
                                     <td><button type="button" value="' + item.id + '" class="edit_student btn btn-primary btn-sm">Edit</button></td>\
                                     <td><button type="button" value="' + item.id + '" class="delete_student btn btn-danger btn-sm">Delete</button></td>\
                                    </tr>');
                        });
                    }
                });
            }
            $(document).on('click', '.edit_student', function(e) {
                e.preventDefault();
                var stud_id = $(this).val(); //atribuindo id a variavel
                //console.log(stud_id);//testando parametro no console
                $('#EditStudentModal').modal('show');
                $.ajax({
                    type: "GET",
                    url: "/edit-student/" + stud_id,
                    success: function(response) {
                        //console.log(response); //testando retorno no console
                        if (response.status == 404) { //Se der ruim retorna abaixo
                            $('#success_message').html("");
                            $('#success_message').addClass('alert alert-danger');
                            $('#success_message').text(response.message);
                        } else { //recuperando os valores
                            $('#edit_stud_id').val(stud_id);
                            $('#edit_name').val(response.student.name);
                            $('#edit_email').val(response.student.email);
                            $('#edit_phone').val(response.student.phone);
                            $('#edit_course').val(response.student.course);
                        }
                    }
                });
            });
            //Atualiza o registro
            $(document).on('click', '.update_student', function(e) {
                e.preventDefault();
                //Texto do botão update
                $(this).text("Updating");

                var stud_id = $('#edit_stud_id').val();
                var data = {
                    'name': $('#edit_name').val(),
                    'email': $('#edit_email').val(),
                    'phone': $('#edit_phone').val(),
                    'course': $('#edit_course').val(),
                }
                // Configurando o token dentro do ajax
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                //definindo rotas, metodos e tipos de dados de resposta
                $.ajax({
                    type: "PUT",
                    url: "/update-student/" + stud_id,
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        //console.log(response);
                        if (response.status == 400) {
                            // erros message
                            $('#updateform_errList').html("");
                            $('#updateform_errList').addClass('alert alert-danger');
                            $.each(response.errors, function(key, err_values) {
                                $('#updateform_errList').append('<li>' + err_values +
                                    '</li>');
                            });
                            $('.update_student').text("Update");
                        } else if (response.status == 404) {
                            // erros message
                            $('#updateform_errList').html("");
                            $('#success_message').addClass('alert alert-danger');
                            $('#success_message').text(response.message);
                            $('.update_student').text("Update");
                        } else {
                            // success message
                            $('#updateform_errList').html("");
                            $('#success_message').html("");
                            $('#success_message').addClass('alert alert-danger');
                            $('#success_message').text(response.message);

                            //Fechar formulário de update
                            $('#EditStudentModal').modal('hide');
                            $('.update_student').text("Update");
                            fetchstudent();//Chamar função de carregamento da informações da tabela
                        }
                    }
                });
            });
            // Cadastro de Students
            $(document).on('click', '.add_student', function(e) {
                e.preventDefault();
                //console.log("Hello WOrld!");
                var data = {
                    'name': $('.name').val(),
                    'email': $('.email').val(),
                    'phone': $('.phone').val(),
                    'course': $('.course').val(),
                }
                //console.log(data);
                // Configurando o token dentro do ajax
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // Método de cadastro dentro do AJAX
                $.ajax({
                    type: "POST",
                    url: "/students",
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        //console.log(response);
                        if (response.status == 400) {
                            $('#saveform_errList').html("");
                            $('#saveform_errList').addClass('alert alert-danger');
                            $.each(response.errors, function(key, err_values) {
                                $('#saveform_errList').append('<li>' + err_values +
                                    '</li>');
                            });
                        } else {
                            $('#saveform_errList').html("");
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('#AddStudentModal').modal('hide');
                            $('#AddStudentModal').find('input').val("");
                            fetchstudent();
                        }
                    }
                });
            });
            // End Cadastro de Students
        });
    </script>
@endsection
