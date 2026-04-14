{{-- UI métier « formulaire type » : en-tête pastel + bouton vert uniforme (liste / création / édition) --}}
<style>
.page-sifec-index .card.mariage-ft-card > .card-header.mariage-ft-head,
.page-sifec-form .card.mariage-ft-card > .card-header.mariage-ft-head {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem 1.25rem;
    background: linear-gradient(135deg, #ecf6ef 0%, #e3f2e8 100%);
    border-bottom: 1px solid #c5e6d1;
    padding: 1rem 1.35rem;
}
.page-sifec-index .card.mariage-ft-card > .card-header.mariage-ft-head h4,
.page-sifec-form .card.mariage-ft-card > .card-header.mariage-ft-head h4 {
    margin: 0;
    font-weight: 700;
    font-size: 1.15rem;
    color: #1e5630;
    letter-spacing: 0.01em;
}
.mariage-ft-head-right {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem 0.65rem;
    justify-content: flex-end;
    flex: 1 1 auto;
    min-width: 0;
}
.mariage-ft-head-right .form-control {
    max-width: 220px;
    width: auto;
}
a.btn-mariage-ft,
button.btn-mariage-ft {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    background: #198754 !important;
    border: 1px solid #157347 !important;
    color: #fff !important;
    border-radius: 0.5rem;
    font-weight: 600;
    padding: 0.5rem 1.15rem;
    text-decoration: none !important;
    box-shadow: 0 2px 8px rgba(25, 135, 84, 0.28);
    white-space: nowrap;
}
a.btn-mariage-ft:hover,
button.btn-mariage-ft:hover {
    filter: brightness(1.06);
    color: #fff !important;
    border-color: #146c43 !important;
}
</style>
