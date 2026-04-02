export function getPayments() {
  return fetch("/api/purchases")
    .then((res) => res.json())
    .then((res) => {
      return res as Purchase[];
    });
}
