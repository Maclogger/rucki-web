#!/bin/bash
# Tento skript automatizuje prípravu branchu na vytvorenie Pull Requestu.
# set -e zabezpečí, že ak akýkoľvek príkaz zlyhá, skript sa okamžite preruší.
set -e

echo "🚀 Spúšťam prípravu na vydanie..."

# 1. KROK: Overenie, či je pracovný adresár čistý
# Aby sme predišli problémom, skript pobeží len ak nemáš rozpracované, necommitnuté zmeny.
if ! git diff-index --quiet HEAD --; then
    echo "❌ CHYBA: Máš necommitnuté zmeny. Prosím, commitni alebo storni ich pred spustením."
    exit 1
fi

# 2. KROK: Synchronizácia s hlavným branchom (master)
# Zabezpečí, že tvoj branch vychádza z najnovšej verzie produkčného kódu.
echo "🔄 Synchronizujem s 'master' branchom..."
git fetch origin master
git rebase origin/master

# 3. KROK: Spustenie produkčného buildu
echo "🛠️  Spúšťam 'npm run build'... (môže to chvíľu trvať)"
bash vendor/bin/sail npm run build

# 4. KROK: Commitnutie zbuildených súborov (ak sa nejaké zmenili)
# Skript skontroluje, či build vôbec vytvoril nejaké zmeny. Ak áno, vytvorí nový commit.
if [ -n "$(git status --porcelain=v1 public/build)" ]; then
    echo "📦 Pridávam zmeny z 'public/build' a vytváram commit..."
    git add public/build
    git commit -m "build: Compile production assets"
else
    echo "✅ 'public/build' je aktuálny, žiadny nový commit nie je potrebný."
fi

# 5. KROK: Pushnutie finálneho stavu na GitHub
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
echo "⬆️  Pushujem branch '$CURRENT_BRANCH' na GitHub..."
git push --force-with-lease origin $CURRENT_BRANCH

echo ""
echo "✅ HOTOVO! Tvoj branch je pripravený na produkciu. Vytvor PR z '$CURRENT_BRANCH' do 'master'."
