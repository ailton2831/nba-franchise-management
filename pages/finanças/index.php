<?php 
include("../../db.php");

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Finanças — NBA Franchise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }

        .metric-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.25rem;
            text-align: center;
        }
        .metric-card .label { font-size: 13px; color: #6c757d; margin-bottom: 6px; }
        .metric-card .value { font-size: 22px; font-weight: 600; margin-bottom: 2px; }
        .metric-card .sub   { font-size: 11px; color: #adb5bd; }
        .metric-card.danger  { background: #fff5f5; border-color: #f5c6c6; }
        .metric-card.danger .label { color: #842029; }
        .metric-card.danger .value { color: #842029; }

        .bar-track { background: #e9ecef; border-radius: 8px; height: 12px; overflow: hidden; }
        .bar-fill  { height: 100%; border-radius: 8px; background: #E24B4A; }

        .section-title {
            font-size: 12px; font-weight: 600; color: #6c757d;
            text-transform: uppercase; letter-spacing: .05em; margin-bottom: 1rem;
        }
        .badge-tipo { font-size: 11px; padding: 3px 8px; border-radius: 20px; font-weight: 500; }
        .badge-jogador { background: #e7f1fb; color: #0c447c; }
        .badge-staff   { background: #f0f7ea; color: #27500a; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand navbar-light bg-light">
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#">🏀NBA Franchise</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="<?php echo $url_base;?>index.php" aria-current="page"
                    >Home page <span class="visually-hidden">(current)</span></a
                >
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/jogadores">Jogadores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/staff">Staff</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/jogos">Jogos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/stats">Stats</a>
            </li>
            <?php //if(isset($_SESSION['tipo']) && $_SESSION['tipo']== 'admin'){ ?>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/utilizadores">Utilizadores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/contratos">Contratos</a>
            </li>
            <?php //if(isset($_SESSION['tipo']) && $_SESSION['tipo']== 'analista'){ ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/finanças">Finanças</a>
            </li>
            
            <?php //} ?>
            <?php //} ?>

                        

                        <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>logout.php">Logout</a>
            </li>

        </ul>
    </nav>

<div class="container-fluid px-4 py-4">

    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0 fw-semibold">Finanças da temporada</h5>
            <small class="text-muted">Temporada 2024/2025</small>
        </div>
        <span class="badge bg-danger fs-6">⚠️ Teto excedido</span>
    </div>

    <!-- Alerta -->
    <div class="alert alert-danger d-flex align-items-center gap-2">
        <span>⚠️</span>
        <span>O teto salarial foi excedido em <strong>$4.500.000</strong>. Reveja os contratos ativos.</span>
    </div>

    <!-- Cards de métricas -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="metric-card">
                <p class="label">📈 Salary cap</p>
                <p class="value text-primary">$123.000.000</p>
                <p class="sub">limite suave</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="metric-card">
                <p class="label">🚩 Teto salarial</p>
                <p class="value text-dark">$150.000.000</p>
                <p class="sub">limite máximo</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="metric-card">
                <p class="label">🧾 Total comprometido</p>
                <p class="value text-warning">$154.500.000</p>
                <p class="sub">contratos ativos</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="metric-card danger">
                <p class="label">🔴 Espaço disponível</p>
                <p class="value">-$4.500.000</p>
                <p class="sub">teto excedido</p>
            </div>
        </div>
    </div>

    <!-- Barra de utilização -->
    <div class="card border-0 shadow-sm mb-4 p-3">
        <p class="section-title mb-2">Utilização do teto salarial</p>
        <div class="d-flex justify-content-between mb-1" style="font-size:12px;color:#6c757d">
            <span>$0</span>
            <span>Salary cap $123M</span>
            <span>Teto $150M</span>
        </div>
        <div class="bar-track">
            <div class="bar-fill" style="width:100%"></div>
        </div>
        <div class="d-flex gap-3 mt-2" style="font-size:12px">
            <span style="color:#155724">■ Dentro do cap</span>
            <span style="color:#856404">■ Entre cap e teto</span>
            <span style="color:#842029">■ Acima do teto</span>
        </div>
    </div>

    <!-- Formulário + Tabela -->
    <div class="row g-4">

        <!-- Formulário -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-3">
                <p class="section-title">Definir temporada</p>
                <form>
                    <div class="mb-3">
                        <label class="form-label">Temporada</label>
                        <input type="text" class="form-control" value="2024/2025" placeholder="ex: 2024/2025">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <label class="form-label">Salary cap ($)</label>
                            <input type="number" class="form-control" value="123000000">
                        </div>
                        <div class="col">
                            <label class="form-label">Teto salarial ($)</label>
                            <input type="number" class="form-control" value="150000000">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">💾 Guardar alterações</button>
                </form>
            </div>
        </div>

        <!-- Tabela de contratos -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-3">
                <p class="section-title">Contratos ativos</p>
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th class="text-end">Salário anual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>LeBron James</td>
                            <td><span class="badge-tipo badge-jogador">Jogador</span></td>
                            <td class="text-end fw-semibold">$47.600.000</td>
                        </tr>
                        <tr>
                            <td>Anthony Davis</td>
                            <td><span class="badge-tipo badge-jogador">Jogador</span></td>
                            <td class="text-end fw-semibold">$43.200.000</td>
                        </tr>
                        <tr>
                            <td>Austin Reaves</td>
                            <td><span class="badge-tipo badge-jogador">Jogador</span></td>
                            <td class="text-end fw-semibold">$12.800.000</td>
                        </tr>
                        <tr>
                            <td>D'Angelo Russell</td>
                            <td><span class="badge-tipo badge-jogador">Jogador</span></td>
                            <td class="text-end fw-semibold">$18.000.000</td>
                        </tr>
                        <tr>
                            <td>Coach Ham</td>
                            <td><span class="badge-tipo badge-staff">Staff</span></td>
                            <td class="text-end fw-semibold">$8.000.000</td>
                        </tr>
                        <tr>
                            <td>Rui Hachimura</td>
                            <td><span class="badge-tipo badge-jogador">Jogador</span></td>
                            <td class="text-end fw-semibold">$17.800.000</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="2" class="fw-semibold">Total</td>
                            <td class="text-end fw-bold text-danger">$154.500.000</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>